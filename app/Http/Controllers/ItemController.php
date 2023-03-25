<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;


class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 商品一覧
     */
    public function index(Request $request)
{
// キーワードを取得する
$keyword = $request->input('keyword');

// 商品一覧を取得する
$items = Item
::where('items.status', 'active')
->when($keyword, function ($query, $keyword) {
// 名前がキーワードに部分一致する商品を取得する
return $query->where('items.name', 'LIKE', "%{$keyword}%");
})
->select()
->get();

return view('item.index', compact('items', 'keyword'));
}
    /**
     * 商品登録
     */
    public function add(Request $request)
    {
        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーション
            $this->validate($request, [
                'name' => 'required|max:100',
            ]);

            // 商品登録
            Item::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'type' => $request->type,
                'detail' => $request->detail,
            ]);

            return redirect('/items');
        }

        return view('item.add');
    }
    /**
 * 商品削除
 */
public function delete($id)
{
    // 商品を削除する
    Item::destroy($id);

    return redirect('/items');
}

/**
 * 検索機能
 */

}
