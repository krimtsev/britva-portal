<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use App\Http\Services\ReportService;
use App\Models\Partner;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\TicketCategory;
use App\Models\Ticket\TicketMessage;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{

    public function isDashboard(Request $request): bool
    {
        return $request->route()->getAction("view") == "dashboard";
    }

    public function isProfile($request): bool
    {
        return $request->route()->getAction("view") == "profile";
    }

    public function dateDiff($ticket)
    {
        $now = Carbon::now();
        $latestMessages = TicketMessage::where('ticket_id', $ticket->id)
            ->latest('created_at')
            ->first();

        if (!$latestMessages) return $now;

        return $latestMessages->created_at->diff($now);
    }

    private function isEditable($partnerId): bool {
        if (!$partnerId) return false;

        return Partner::sqlAvailable()
            ->where('id', $partnerId)
            ->exists();
    }

    static function sendMessage($ticket) {
        $type = "ticket";
        $msg = "Создана новая заявка";

        $partner_id = $ticket["partner_id"];
        $category_id = $ticket["category_id"];

        $partner = Partner::select("name")
            ->where("id", $partner_id)
            ->first();

        $category = TicketCategory::select("title")
            ->where("id", $category_id)
            ->first();

        $data = [];
        $data["title"] = $ticket["title"];
        $data["company_name"] = $partner["name"];
        $data["category"] = $category["title"];

        ReportService::msg($type, $msg, $data);
    }

    public function index(Request $request)
    {
        $isProfile = $this->isProfile($request);
        $isDashboard = $this->isDashboard($request);

        $user = Auth::user();
        $isAllowedEditInProfile = false;

        $sql = Ticket::orderBy('id', 'DESC');

        if ($isProfile) {
            $sql->where('partner_id', $user->partner_id);

            $isAllowedEditInProfile = Partner::sqlAvailable()
                ->where('id', $user->partner_id)
                ->exists();
        }

        $filter['category'] = $request->input("filter_category");
        $filter['partner'] = $request->input("filter_partner");
        $filter['state'] = $request->input("filter_state");

        if ($filter['category']) {
            $sql->where("category_id", $filter['category']);
        }

        if ($filter['partner']) {
            $sql->where("partner_id", $filter['partner']);
        }

        if ($filter['state']) {
            $sql->where("state", $filter['state']);
        }

        $tickets = $sql->paginate(30);

        foreach ($tickets as $ticket) {
            $date = self::dateDiff($ticket);
            $ticket->daysInWork = $date->days;
            $ticket->dayName = $date->format('%dд. %H:%I');
        }

        $stateList = [];
        $categories = [];
        $partners = [];

        if ($isDashboard) {
            $stateList = Ticket::stateList;

            $categories =  TicketCategory::select('id', 'title')
                ->orderBy('title', 'ASC')
                ->get();

            $partners =  Partner::available();
        }

        if ($isProfile) {
            return view('profile.tickets.list.index', compact(
                'tickets',
                'isAllowedEditInProfile'
            ));
        }

        return view('dashboard.tickets.list.index', compact(
            'tickets',
            'filter',
            'categories',
            'partners',
            'stateList'
        ));
    }

    /**
     * $topic - slug для шаблона TicketsQuestions
     */
    public function create(Request $request, $topic = null)
    {
        $isProfile = $this->isProfile($request);
        $user = Auth::user();

        if ($isProfile && !self::isEditable($user->partner_id)) {
            return redirect()->route('p.tickets.index');
        }

        $categories = TicketCategory::whereNull('deleted_at')
            ->get();

        $sql = Partner::sqlAvailable();

        if ($user->partner_id || $isProfile) {
            $sql->where('id', $user->partner_id);
        }

        $partners = $sql->get();

        $questions = [];
        if ($topic) {
            $questions = TicketsQuestions::getData($topic);
        }

        if ($isProfile) {
            return view('profile.tickets.list.create', compact(
                'categories',
                'partners',
                'questions',
                'topic',
            ));
        }

        return view('dashboard.tickets.list.create', compact(
            'categories',
            'partners'
        ));
    }

    public function store(Request $request, $topic = null): \Illuminate\Http\RedirectResponse
    {
        $isProfile = $this->isProfile($request);
        $isDashboard = $this->isDashboard($request);

        $user = Auth::user();
        $partner_id = $user->partner_id;

        if ($isProfile && !self::isEditable($partner_id)) {
            return redirect()->route('p.tickets.index');
        }

        $rules = [
            'title'       => ['required', 'string'],
            'category_id' => ['required'],
            'text'        => ['required', 'string'],
            'files.*'     => ['nullable', TicketsFilesController::RULES_ALLOW_TYPES]
        ];

        // Для панели администратора передача partner_id обязательна
        if ($isDashboard) {
            $rules['partner_id'] = ['required', 'string'];
            $errors['partner_id.required'] = "Выберите филиал";
        }

        $errors = [
            'title.required' => 'Укажите тему запроса',
            'category_id.required' => 'Выберите отдел',
            'text.required' => 'Сообщение не может быть пустым',
        ];

        if (TicketsQuestions::isExist($topic)) {
            foreach (TicketsQuestions::getQuestions($topic) as $question) {
                $rules[$question["key"]] = $question['rules'];
                $errors[$question["key"].".required"] = "Необходимо заполнить: {$question['text']}";
            }

            request()->merge(['category_id' => TicketsQuestions::getCategory($topic)]);
            request()->merge(['title' => TicketsQuestions::getTitle($topic)]);

            /**
             * Если заявка пришла не из топика с вопросам, тогда поле text не обязательное для заполенения
             */
            $rules['text'] = [];
        }

        $validated = request()->validate($rules, $errors);

        if (array_key_exists('partner_id', $validated)) {
            $partner_id = $validated['partner_id'];
        }

        $user = Auth::user();

        $data = [
            'title'       => $validated['title'],
            'category_id' => $validated['category_id'],
            'partner_id'  => $partner_id,
            'user_id'     => $user->id,
        ];

        $ticket = Ticket::create($data);

        self::sendMessage($data);

        $list = [];
        if (TicketsQuestions::isExist($topic)) {
            foreach (TicketsQuestions::getQuestions($topic) as $question) {
                $list[] = "<b>" . $question["text"] . "</b>";
                $list[] = $validated[$question["key"]] . "\n";
            }
        }

        if (array_key_exists('text', $validated)) {
            $list[] = $validated['text'];
        }

        $text = implode("\n", $list);

        $ticket_message = TicketMessage::create([
            'text'         => $text,
            'ticket_id'    => $ticket->id,
            'user_id'      => $user->id,
        ]);

        if (array_key_exists("files", $validated)) {
            foreach ($validated["files"] as $file) {
                TicketsFilesController::addFile($ticket->id, $ticket_message->id, $file);
            }
        }

        if ($isProfile) {
            return redirect()->route('p.tickets.edit', $ticket->id);
        }

        return redirect()->route('d.tickets.edit', $ticket->id);
    }

    public function edit(Ticket $ticket, Request $request)
    {
        $isProfile = $this->isProfile($request);
        $isDashboard = $this->isDashboard($request);

        $user = Auth::user();

        if ($isProfile && ($ticket->partner_id != $user->partner_id || !self::isEditable($user->partner_id))) {
            return redirect()->route('p.tickets.index');
        }

        $messages = TicketMessage::where('ticket_id', $ticket->id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC')
            ->get();

        $categories = [];

        if ($isDashboard) {
            $categories = TicketCategory::select("id", "title")
                ->get();

            $partners = Partner::available();
        }


        $stateList = Ticket::stateList;

        if ($isProfile) {
            return view('profile.tickets.list.edit', compact(
                'ticket',
                'messages',
                'stateList'
            ));
        }

        return view('dashboard.tickets.list.edit', compact(
            'ticket',
            'messages',
            'stateList',
            'categories',
            'partners'
        ));
    }

    // Обнопить данные записи
    public function update(Ticket $ticket, Request $request)
    {
        $isProfile = $this->isProfile($request);
        $user = Auth::user();

        if ($isProfile && ($ticket->partner_id != $user->partner_id || !self::isEditable($user->partner_id))) {
            return redirect()->route('p.tickets.index');
        }

        $validated = request()->validate([
            'title'       => ['required', 'string'],
            'state'       => ['required', 'string'],
            'category_id' => ['required', 'string'],
            'partner_id'  => ['required', 'string'],
        ], [
            'title.required' => 'Тема запроса обязательна для заполнения'
        ]);

        $data = [
            'title'       => $validated['title'],
            'state'       => $validated['state'],
            'category_id' => $validated['category_id'],
            'partner_id'  => $validated['partner_id'],
        ];

        $ticket->update($data);

        if ($isProfile) {
            return redirect()->route('p.tickets.edit', $ticket->id);
        }

        return redirect()->route('d.tickets.edit', $ticket->id);
    }

    public function updateMessage(Ticket $ticket, Request $request)
    {
        $isProfile = $this->isProfile($request);
        $user = Auth::user();

        if ($isProfile && !self::isEditable($user->partner_id)) {
            return redirect()->route('p.tickets.index');
        }

        $validated = request()->validate([
            'text'    => ['required', 'string'],
            'files.*' => ['nullable', TicketsFilesController::RULES_ALLOW_TYPES]
        ], [
            'text.required' => 'Сообщение обязательно для заполнения'
        ]);

        $ticket_message = TicketMessage::create([
            'text'         => $validated['text'],
            'ticket_id'    => $ticket->id,
            'user_id'      => $user->id,
        ]);

        if (Utils::isNotEmptyArrayKey($validated, 'files')) {
            foreach ($validated["files"] as $file) {
                TicketsFilesController::addFile($ticket->id, $ticket_message->id, $file);
            }
        }

        if ($isProfile) {
            return redirect()->route('p.tickets.edit', $ticket->id);
        }

        return redirect()->route('d.tickets.edit', $ticket->id);
    }

    public function state(Ticket $ticket, Request $request): \Illuminate\Http\RedirectResponse
    {
        $isProfile = $this->isProfile($request);
        $user = Auth::user();

        if ($isProfile && !self::isEditable($user->partner_id)) {
            return redirect()->route('p.tickets.index');
        }

        $state = in_array($ticket->state, Ticket::stateIdsClosed) ? 1 : 5;

        $ticket->fill([
            'state' => $state
        ]);

        $ticket->save();

        if ($isProfile) {
            return redirect()->route('p.tickets.edit', $ticket->id);
        }

        return redirect()->route('d.tickets.edit', $ticket->id);
    }

    public function delete(Ticket $ticket): \Illuminate\Http\RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('d.tickets.index');
    }
}
