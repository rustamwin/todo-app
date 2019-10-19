<?php

namespace App\Http\Resources;

use App\Todo;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TodoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->title,
            'children' => $this->children,
        ];
    }

    public static function sortTree(array $data, ?int $parent = null)
    {
        $pos = 0;
        foreach ($data as $item) {
            if ($todo = Todo::find((int)$item['id'])) {
                $todo->parent_id = $parent;
                $todo->position = $pos++;
                $todo->save();
                if (isset($item['children']))
                    self::sortTree($item['children'], $todo->id);
            }
        }
        return true;
    }
}
