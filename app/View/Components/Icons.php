<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Icons extends Component
{
    public $name;
    public $size;

    const DEFAULT_FILE = "default";
    const DEFAULT_SIZE = 48;

    public $list = [
        "folder" => "folder",
        "doc"    => "doc",
        "docx"   => "doc",
        "xls"    => "xls",
        "xlsx"   => "xls",
        "pdf"    => "pdf",
        "txt"    => "txt",
        "png"    => "image",
        "jpg"    => "image",
        "jpeg"   => "image",
        "tif"    => "image",
        "ppt"    => "ppt",
        "zip"    => "zip",
        "7zip"   => "zip",
    ];

    public function __construct($name = self::DEFAULT_FILE, $size = self::DEFAULT_SIZE)
    {
        $this->name = $name;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        $name = self::DEFAULT_FILE;

        if (array_key_exists($this->name, $this->list)) {
            $name = $this->list[$this->name];
        }

        $src = asset("assets/icons/{$name}.png");

        return view('components.icons', compact("src", "name"));
    }
}
