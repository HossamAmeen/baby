<?php

namespace App\Repositories;

class OrderRepository
{

    public static function takeProductQuantity(&$order, &$product, $quantity)
    {

        $product->stock -= $quantity;
        $main_stock = 0;
        $sub_stock = 0;
        $store_stock = 0;

        if ($product->main_stock > 0 && $quantity > 0) {
            if ($product->main_stock >= $quantity) {
                $product->main_stock -= $quantity;
                $main_stock = $quantity;
            } else if ($product->main_stock < $quantity) {
                $main_stock = $product->main_stock;
                $product->main_stock -= $product->main_stock;
            }
            $quantity = $quantity - ($main_stock);
        }


        if ($product->sub_stock > 0 && $quantity > 0) {
            if ($product->sub_stock >= $quantity) {
                $product->sub_stock -= $quantity;
                $sub_stock = $quantity;
            } else if ($product->sub_stock < $quantity) {
                $sub_stock = $product->sub_stock;
                $product->sub_stock -= $product->sub_stock;
            }
            $quantity = $quantity - ($sub_stock);
        }


        if ($product->store_stock > 0 && $quantity > 0) {
            if ($product->store_stock >= $quantity) {
                $product->store_stock -= $quantity;
                $store_stock = $quantity;
            } else if ($product->store_stock < $quantity) {
                $store_stock = $product->store_stock;
                $product->store_stock -= $product->store_stock;
            }
        }
        $order->main_stock += $main_stock;
        $order->sub_stock += $sub_stock;
        $order->store_stock += $store_stock;
    }

    public static function backProductQuantity(&$order, &$product, $quantity)
    {
        $product->stock += $quantity;
        $main_stock = 0;
        $sub_stock = 0;
        $store_stock = 0;

        if ($order->main_stock === null && $order->sub_stock === null
            && $order->store_stock === null) {
            $main_stock += $quantity;
            $quantity = $quantity - $main_stock;
        }else {

            if ($order->main_stock > 0 && $quantity > 0) {
                if ($order->main_stock >= $quantity) {
                    $order->main_stock -= $quantity;
                    $main_stock = $quantity;
                } else if ($order->main_stock < $quantity) {
                    $main_stock = $order->main_stock;
                    $order->main_stock -= $order->main_stock;
                }
                $quantity = $quantity - ($main_stock);
            }

            if ($order->sub_stock > 0 && $quantity > 0) {
                if ($order->sub_stock >= $quantity) {
                    $order->sub_stock -= $quantity;
                    $sub_stock = $quantity;
                } else if ($order->sub_stock < $quantity) {
                    $sub_stock = $order->sub_stock;
                    $order->sub_stock -= $order->sub_stock;
                }
                $quantity = $quantity - ($sub_stock);
            }

            if ($order->store_stock > 0 && $quantity > 0) {
                if ($order->store_stock >= $quantity) {
                    $order->store_stock -= $quantity;
                    $store_stock = $quantity;
                } else if ($order->store_stock < $quantity) {
                    $store_stock = $order->store_stock;
                    $order->store_stock -= $order->store_stock;
                }
            }
        }

        $product->main_stock += $main_stock;
        $product->sub_stock += $sub_stock;
        $product->store_stock += $store_stock;
    }


    //////////////////////////////////
    ///

    public static function takePlaceProductQuantity(&$order, &$product, $quantity)
    {
        $product->stock -= $quantity;
        $main_stock = 0;
        $sub_stock = 0;
        $store_stock = 0;

        if ($product->store_stock > 0 && $quantity > 0) {
            if ($product->store_stock >= $quantity) {
                $product->store_stock -= $quantity;
                $store_stock = $quantity;
            } else if ($product->store_stock < $quantity) {
                $store_stock = $product->store_stock;
                $product->store_stock -= $product->store_stock;
            }
            $quantity = $quantity - ($store_stock);
        }

        if ($product->main_stock > 0 && $quantity > 0) {
            if ($product->main_stock >= $quantity) {
                $product->main_stock -= $quantity;
                $main_stock = $quantity;
            } else if ($product->main_stock < $quantity) {
                $main_stock = $product->main_stock;
                $product->main_stock -= $product->main_stock;
            }
            $quantity = $quantity - ($main_stock);
        }

        if ($product->sub_stock > 0 && $quantity > 0) {
            if ($product->sub_stock >= $quantity) {
                $product->sub_stock -= $quantity;
                $sub_stock = $quantity;
            } else if ($product->sub_stock < $quantity) {
                $sub_stock = $product->sub_stock;
                $product->sub_stock -= $product->sub_stock;
            }
        }

        $order->main_stock += $main_stock;
        $order->sub_stock += $sub_stock;
        $order->store_stock += $store_stock;
    }

    public static function backPlaceProductQuantity(&$order, &$product, $quantity)
    {
        $product->stock += $quantity;
        $main_stock = 0;
        $sub_stock = 0;
        $store_stock = 0;

        if ($order->main_stock === null && $order->sub_stock === null
            && $order->store_stock === null) {
            $main_stock += $quantity;
            $quantity = $quantity - $main_stock;
        }else {
            if ($order->store_stock > 0 && $quantity > 0) {
                if ($order->store_stock >= $quantity) {
                    $order->store_stock -= $quantity;
                    $store_stock = $quantity;
                } else if ($order->store_stock < $quantity) {
                    $store_stock = $order->store_stock;
                    $order->store_stock -= $order->store_stock;
                }
                $quantity = $quantity - ($store_stock);
            }

            if ($order->main_stock > 0 && $quantity > 0) {
                if ($order->main_stock >= $quantity) {
                    $order->main_stock -= $quantity;
                    $main_stock = $quantity;
                } else if ($order->main_stock < $quantity) {
                    $main_stock = $order->main_stock;
                    $order->main_stock -= $order->main_stock;
                }
                $quantity = $quantity - ($main_stock);
            }

            if ($order->sub_stock > 0 && $quantity > 0) {
                if ($order->sub_stock >= $quantity) {
                    $order->sub_stock -= $quantity;
                    $sub_stock = $quantity;
                } else if ($order->sub_stock < $quantity) {
                    $sub_stock = $order->sub_stock;
                    $order->sub_stock -= $order->sub_stock;
                }
            }

        }

        $product->main_stock += $main_stock;
        $product->sub_stock += $sub_stock;
        $product->store_stock += $store_stock;
    }

}