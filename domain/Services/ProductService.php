<?php

namespace domain\Services;

use App\Models\Product;
use Exception;
use GuzzleHttp\Psr7\Message;

class ProductService
{
    protected $item;
    public function __construct()
    {
        $this->item = new Product();
    }
    public function all()
    {
        return $this->item->all();
    }
    public function store($data)
    {
        $this->item->create($data->all());
    }
    public function get($item_id)
    {
        return $this->item->find($item_id);
    }
    public function update($data, $item_id)
    {
        $item = $this->item->find($item_id);
        $item->update($this->edit($item, $data));
        
    }
    public function delete($item_id)
    {
        $item = $this->item->find($item_id);
        $item->delete();
    }
    protected function edit(Product $item, $data)
    {
        return array_merge($item->toArray(), $data->toArray());
    }
    public function validation($data)
    {
        try{
        $sku = $data->sku;
        $name = $data->name;
        $price = $data->price;
        $image = $data->imageFile;
        $msg = $this->skuDuplication($sku);
        $imgValidate = $this->imageValidation($data);
        if ($msg === true) {
            if ($sku == "") {
                $msg = "Please enter SKU";
            } elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $sku) ||  strlen($sku) > 100) {
                $msg = "Please, provide a valid SKU";
            }
            elseif (empty($name)) {
                $msg = "Please enter name";
            } elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $name) ||  strlen($name) > 100) {
                $msg = "Please, provide a valid name";
            } elseif (empty($price)) {
                $msg = "Please enter price";
            } elseif (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0.1) {
                $msg = "Please, provide a valid Price";
            } elseif (!empty($image) && $imgValidate !== true) {
                $msg = $imgValidate;
            } else {
                $response = ['status' => true, 'message' => null];
                return $response;
            }
        }
        $response = ['status' => false, 'message' => $msg];
        return $response;}
        catch(Exception $e){
            return $e->getMessage();
        }
    }
    public function editValidation($data)
    {
        
        $sku = $data->sku;
        $name = $data->name;
        $price = $data->price;
        $image = $data->image;
        $item = $this->item->find($data->id);
        $currentSku = $item->sku;
        $imgValidate = $this->imageValidation($data);

         if($sku !== $currentSku){
            $msg = $this->skuDuplication($sku);
         }
         else{
            $msg = true;
        }
        if ($msg === true) {
            if ($sku == "") {
                $msg = "Please enter SKU";
            } elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $sku) ||  strlen($sku) > 100) {
                $msg = "Please, provide a valid SKU";
            }
            elseif (empty($name)) {
                $msg = "Please enter name";
            } elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $name) ||  strlen($name) > 100) {
                $msg = "Please, provide a valid name";
            } elseif (empty($price)) {
                $msg = "Please enter price";
            } elseif (!filter_var($price, FILTER_VALIDATE_FLOAT) || $price < 0.1) {
                $msg = "Please, provide a valid Price";
            } elseif (!empty($image) && $imgValidate !== true) {
                $msg = $imgValidate;
            } else {
                $response = ['status' => true, 'message' => null];
                return $response;
            }
        }
        $response = ['status' => false, 'message' => $msg];
        return $response;
    }
    // protected function skuValidation($sku)
    // {
    //     //$skuLower = strtolower($sku);
    //     $result = $this->skuDuplication($sku);
    //     if ($result === true) {
    //         if ($sku == "") {
    //             $result = "Please enter SKU";
    //         } elseif (!preg_match("/^([A-Za-z 0-9]+)$/", $sku) ||  strlen($sku) > 100) {
    //             $result = "Please, provide a valid SKU";
    //         }
    //         return $result;
    //     }
    //     return $result;
    // }
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
    protected function imageValidation($data)
    {
         if ($data->hasFile('imageFile')) {
            try {
                $data->validate([
                    'imageFile' => 'mimes:png,jpg,jpeg|max:2048'
                ], [
                    'imageFile.required' => 'requires an image',
                    'imageFile.mimes' => 'The image file should be JPG, JPEG or PNG',
                    'imageFile.max' => 'The maximum allowed image size is 2mb'
                ]);
                return true;
            } catch (Exception $m) {
                return $m->getMessage();
            }
        } else {
            return true;
        }
     }
}
