<?php

namespace App\Http\Controllers\Statements;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Statement\Statement;
use App\Models\Statement\StatementCategory;
use App\Models\Statement\StatementMessage;
use App\Utils\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatementsController extends Controller
{

    public function isDashboard($request): bool
    {
        return $request->route()->getAction("view") == "dashboard";
    }

    public function dateDiff($statement): \DateInterval
    {
        $created = new Carbon($statement->created_at);

        if (in_array($statement->state, [4,5,6])) {
            $updated = new Carbon($statement->updated_at);
            return $created->diff($updated);
        }

        $now = Carbon::now();
        return $created->diff($now);
    }

    public function index(Request $request)
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();
        $partnerId = $user->partner_id;

        $sql = Statement::orderBy('id', 'DESC');

        if (!$isDashboard) {
            $sql->where('partner_id', $partnerId);
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

        $statements = $sql->paginate(30);

        foreach ($statements as $statement) {
            $date = self::dateDiff($statement);
            $statement->daysInWork = $date->days;
            $statement->dayName = $date->format('%dд. %H:%I');
        }

        $stateList = [];
        $categories = [];
        $partners = [];

        if ($isDashboard) {
            $stateList = Statement::stateList;

            $categories =  StatementCategory::select('id', 'title')
                ->orderBy('title', 'ASC')
                ->get();

            $partners =  Partner::available();
        }

        if (!$isDashboard) {
            return view('profile.statements.list.index', compact(
                'statements',
                'partnerId',
            ));
        }

        return view('dashboard.statements.list.index', compact(
            'statements',
            'filter',
            'categories',
            'partners',
            'stateList'
        ));
    }

    public function create(Request $request)
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();

        if (!$isDashboard && !$user->partner_id) {
            return redirect()->route('p.statements.index');
        }

        $categories = StatementCategory::whereNull('deleted_at')
            ->get();

        $sql = Partner::select("id", "name");

        if ($user->partner_id || !$isDashboard) {
            $sql->where('id', $user->partner_id);
        }

        $partners = $sql->get();

        if (!$isDashboard) {
            return view('profile.statements.list.create', compact('categories', 'partners'));
        }

        return view('dashboard.statements.list.create', compact('categories', 'partners'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();
        $partner_id = $user->partner_id;

        if (!$isDashboard && !$partner_id) {
            return redirect()->route('p.statements.index');
        }

        $rules = [
            'title'       => ['required', 'string'],
            'category_id' => ['required', 'string'],
            'text'        => ['required', 'string'],
            'files.*'     => ['nullable', StatementsFilesController::RULES_ALLOW_TYPES]
        ];

        // Для панели администратора передача partner_id обязательна
        if ($isDashboard) {
            $rules['partner_id'] = ['required', 'string'];
        }

        $validated = request()->validate($rules);

        if (array_key_exists('partner_id', $validated)) {
            $partner_id = $validated['partner_id'];
        }

        $user = Auth::user();

        $statement = Statement::create([
            'title'       => $validated['title'],
            'category_id' => $validated['category_id'],
            'partner_id'  => $partner_id,
            'user_id'     => $user->id,
        ]);

        $statement_message = StatementMessage::create([
            'text'         => $validated['text'],
            'statement_id' => $statement->id,
            'user_id'      => $user->id,
        ]);

        if (array_key_exists("files", $validated)) {
            foreach ($validated["files"] as $file) {
                StatementsFilesController::addFile($statement->id, $statement_message->id, $file);
            }
        }

        if (!$isDashboard) {
            return redirect()->route('p.statements.edit', $statement->id);
        }

        return redirect()->route('d.statements.edit', $statement->id);
    }

    public function edit(Statement $statement, Request $request)
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();

        if (!$isDashboard && $statement->partner_id != $user->partner_id) {
            return redirect()->route('p.statements.index');
        }

        $messages = StatementMessage::where('statement_id', $statement->id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC')
            ->get();

        $categories = [];

        if ($isDashboard) {
            $categories = StatementCategory::select("id", "title")
                ->get();

            $partners = Partner::available();
        }


        $stateList = Statement::stateList;

        if (!$isDashboard) {
            return view('profile.statements.list.edit', compact(
                'statement',
                'messages',
                'stateList'
            ));
        }

        return view('dashboard.statements.list.edit', compact(
            'statement',
            'messages',
            'stateList',
            'categories',
            'partners'
        ));
    }

    // Обнопить данные записи
    public function update(Statement $statement, Request $request)
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();

        if (!$isDashboard && $statement->partner_id != $user->partner_id) {
            return redirect()->route('p.statements.index');
        }

        $validated = request()->validate([
            'title'       => ['required', 'string'],
            'state'       => ['required', 'string'],
            'category_id' => ['required', 'string'],
            'partner_id'  => ['required', 'string'],
        ], [
            'title.required' => 'Тема запроса обязательна для заполнения'
        ]);

        $statement->update([
            'title'       => $validated['title'],
            'state'       => $validated['state'],
            'category_id' => $validated['category_id'],
            'partner_id'  => $validated['partner_id'],
        ]);

        if (!$isDashboard) {
            return redirect()->route('p.statements.edit', $statement->id);
        }

        return redirect()->route('d.statements.edit', $statement->id);
    }

    public function updateMessage(Statement $statement, Request $request)
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();

        if (!$isDashboard && $statement->partner_id != $user->partner_id) {
            return redirect()->route('p.statements.index');
        }

        $validated = request()->validate([
            'text'    => ['required', 'string'],
            'files.*' => ['nullable', StatementsFilesController::RULES_ALLOW_TYPES]
        ], [
            'text.required' => 'Сообщение обязательно для заполнения'
        ]);

        $statement_message = StatementMessage::create([
            'text'         => $validated['text'],
            'statement_id' => $statement->id,
            'user_id'      => $user->id,
        ]);

        if (Utils::isNotEmptyArrayKey($validated, 'files')) {
            foreach ($validated["files"] as $file) {
                StatementsFilesController::addFile($statement->id, $statement_message->id, $file);
            }
        }

        if (!$isDashboard) {
            return redirect()->route('p.statements.edit', $statement->id);
        }

        return redirect()->route('d.statements.edit', $statement->id);
    }

    public function state(Statement $statement, Request $request): \Illuminate\Http\RedirectResponse
    {
        $isDashboard = $this->isDashboard($request);
        $user = Auth::user();

        if (!$isDashboard && $statement->partner_id != $user->partner_id) {
            return redirect()->route('p.statements.index');
        }

        $state = in_array($statement->state, Statement::stateIdsClosed) ? 1 : 5;

        $statement->fill([
            'state' => $state
        ]);

        $statement->save();

        if (!$isDashboard) {
            return redirect()->route('p.statements.edit', $statement->id);
        }

        return redirect()->route('d.statements.edit', $statement->id);
    }
}
