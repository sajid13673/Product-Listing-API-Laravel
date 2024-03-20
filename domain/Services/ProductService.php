<?php

namespace domain\Services;

use App\Models\Product;
use App\Models\Image;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class ProductService
{
    protected $item;
    protected $pic;

    public function __construct()
    {
        $this->item = new Product();
        $this->pic = new Image();
    }
    public function all($request)
    {
        if($request->sort){
            $products = $this->item->with(['images' => function ($query) {
                return $query->select(['id', 'imageName', 'imageLink']);
            }])->orderBy($request->sort, $request->order)->get();
            return $products;
        }
        $products = $this->item->with(['images' => function ($query) {
            return $query->select(['id', 'imageName', 'imageLink']);
        }])->get();
        return $products;
    }
    public function store($data)
    {
        try {
            $imageName = $data['imageName'];
            if (!empty($imageName)) {
                $img = $this->pic->create($data->all());
                $data['image_id'] = $img['id'];
            }
            $this->item->create($data->all());
            return response()->json(["status" => true, "message" => "Product Successfully Added"], 200);
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()], 500);
        }
    }
    public function get($item_id)
    {
        return $this->item->with('images')->find($item_id);
    }
    public function update($data, $item_id)
    {
        try {
            $item = $this->item->find($item_id);
            //If formData has an image file
            if ($data->hasFile('imageFile')) {
                $imgId = $item['image_id'];
                $image = $this->pic->find($imgId);
                //If the item does not have an image
                if ($image === null) {
                    $image = $this->pic->create($data->all());
                    $data['image_id'] = $image['id'];
                }
                //If the item already has an image
                else {
                    $image->update($data->toArray());
                }
            }
            $item->update($this->edit($item, $data));
            return response()->json(["status" => true, "message" => "Product Successfully Updated"], 200);
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()], 500);
        }
    }
    public function delete($item_id)
    {
        try {
            $item = $this->item->find($item_id);
            $imgId = $item['image_id'];
            $item->delete();
            if ($imgId !== null) {
                $img = $this->pic->find($imgId);
                $img->delete();
            }
            return response()->json(['status' => true, "message" => "Product Successfully Deleted"], 200);
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()], 500);
        }
    }
    protected function edit(Product $item, $data)
    {
        return array_merge($item->toArray(), $data->toArray());
    }
    protected function skuDuplication($sku)
    {
        $skuLower = strtolower($sku);
        $products = $this->item->all();
        foreach ($products as $product) {
            $productSkuLower = strtolower($product['sku']);
            if ($skuLower === $productSkuLower) {
                return  "Sku already exists";
            }
        }
        return true;
    }
    // protected function imageValidation($data)
    // {
    //     if ($data->hasFile('imageFile')) {
    //         try {
    //             $data->validate([
    //                 'imageFile' => 'mimes:png,jpg,jpeg|max:2048'
    //             ], [
    //                 'imageFile.required' => 'requires an image',
    //                 'imageFile.mimes' => 'The image file should be JPG, JPEG or PNG',
    //                 'imageFile.max' => 'The maximum allowed image size is 2mb'
    //             ]);
    //             return true;
    //         } catch (Exception $m) {
    //             return $m->getMessage();
    //         }
    //     } else {
    //         return true;
    //     }
    // }
}
