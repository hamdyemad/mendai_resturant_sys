<div class="invoice">
    <div class="row">
        <div class="col-12">
            <div class="invoice-title d-flex justify-content-between">
                <h4 class="font-size-16"><strong>رقم الطلب ({{ $order->id }})</strong></h4>
                <h4 class="font-size-16">
                    <strong>({{ $order->created_at }})</strong>
                    <strong class="d-block text-center mt-1">({{ $order->created_at->diffForHumans() }})</strong>
                </h4>
            </div>
            <hr>
            <div class="row">
                @if($order->customer_name)
                    <div class="col first">
                        <strong class="d-block mb-2">معلومات الطلب</strong>
                        <div class="d-flex align-items-center mt-2">
                            <strong class="d-block mr-2">الأسم : </strong>
                            <span class="badge badge-primary">{{ $order->customer_name }}</span>
                        </div>
                        @if($order->customer_phone)
                            <div class="d-flex align-items-center mt-2">
                                <strong class="d-block mr-2">الهاتف : </strong>
                                <span class="badge badge-primary">{{ $order->customer_phone }}</span>
                            </div>
                        @endif
                        @if($order->customer_address)
                            <div class="d-flex align-items-center mt-2">
                                <strong class="d-block mr-2">العنوان : </strong>
                                <span class="badge badge-primary">{{ $order->customer_address }}</span>
                            </div>
                        @endif
                        @if($order->city)
                            <div class="d-flex align-items-center mt-2">
                                <strong class="d-block mr-2">المدينة : </strong>
                                <span class="badge badge-primary">{{ $order->city->name }}</span>
                            </div>
                            <div class="d-flex align-items-center mt-2">
                                <strong class="d-block mr-2">البلد : </strong>
                                <span class="badge badge-primary">{{ $order->city->country->name }}</span>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="col last">
                    @if (get_setting('logo'))
                        <img src="{{ asset(get_setting('logo')) }}" alt="">
                    @else
                        <img src="{{ asset('/images/default.jpg') }}" alt="">
                    @endif
                    @if($order->type == 'inhouse')
                        <div class="d-flex align-items-center">
                            <strong class="d-block mr-2">فرع الطلب : </strong>
                            <span class="badge badge-primary">{{ $order->branch->name }}</span>
                        </div>
                    @endif
                    @if($order->notes)
                        <div class="d-flex align-items-center mt-2">
                            <strong class="d-block mr-2">الملاحظات : </strong>
                            <span class="badge badge-primary">{{ $order->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <div>
                <div class="p-2">
                    <h3 class="font-size-16"><strong>ملخص الطلب</strong></h3>
                </div>
                <div class="">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td><strong>أسم الأكلة</strong></td>
                                    <td><strong>السعر</strong></td>
                                    <td><strong>العدد</strong></td>
                                    <td><strong>السعر الكلى </strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($order->order_details->groupBy('variant_type')['']))
                                    @foreach ($order->order_details->groupBy('variant_type')[''] as $variant)
                                        <tr>
                                            <td><strong>{{ $variant->product->name }}</strong></td>
                                            <td>
                                                <strong>{{ $variant->price }}</strong>
                                                @if(isset($order->order_details->groupBy('variant_type')['extra']))
                                                    @foreach ($order->order_details->groupBy('variant_type')['extra']->groupBy('product_id') as $product_id_from_extra => $val)
                                                        @if($variant->product->id == $product_id_from_extra)
                                                            @foreach ($val as $extra)
                                                                <div class="mb-2 d-flex align-items-center">
                                                                    <div class="line">
                                                                        <strong>الأضافة :</strong>
                                                                        <span class="badge badge-secondary">{{ $extra->variant  }}</span>
                                                                    </div>
                                                                    <div class="line">
                                                                        <strong>السعر :</strong>
                                                                        <span class="badge badge-secondary">{{ $extra->price  }}</span>
                                                                    </div>
                                                                    <div class="line">
                                                                        <strong>الكمية :</strong>
                                                                        <span class="badge badge-secondary">{{ $extra->qty  }}</span>
                                                                    </div>
                                                                    @if($extra->qty > 1)
                                                                        <div class="line">
                                                                            <strong>السعر الكلى :</strong>
                                                                            <span class="badge badge-secondary">{{ $extra->total_price  }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td><strong>{{ $variant->qty }}</strong></td>
                                            <td><strong>{{ $variant->total_price }}</strong></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(isset($order->order_details->groupBy('variant_type')['size']))
                                    @foreach ($order->order_details->groupBy('variant_type')['size']->groupBy('product_id') as $product_id_from_size => $value)
                                        <tr>
                                            <td>
                                                <h6><strong>{{ App\Models\Product::find($product_id_from_size)->name }}</strong></h6>
                                            </td>
                                            <td>
                                                @foreach ($value as $variant)
                                                    <div class="mb-2 d-flex align-items-center">
                                                        <div class="line">
                                                            <strong>الحجم :</strong>
                                                            <span class="badge badge-secondary">{{ $variant->variant  }}</span>
                                                        </div>
                                                        <div class="line">
                                                            <strong>السعر :</strong>
                                                            <span class="badge badge-secondary">{{ $variant->price  }}</span>
                                                        </div>
                                                        <div class="line">
                                                            <strong>العدد :</strong>
                                                            <span class="badge badge-secondary">{{ $variant->qty  }}</span>
                                                        </div>
                                                        @if($variant->qty > 1)
                                                            <div class="line">
                                                                <strong>السعر الكلى :</strong>
                                                                <span class="badge badge-secondary">{{ $variant->total_price  }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                @if(isset($order->order_details->groupBy('variant_type')['extra']))
                                                    @foreach ($order->order_details->groupBy('variant_type')['extra']->groupBy('product_id') as $product_id_from_extra => $val)
                                                        @if($product_id_from_extra == $product_id_from_size)
                                                            @foreach ($val as $variant)
                                                                <div class="mb-2 d-flex align-items-center">
                                                                    <div class="line">
                                                                        <strong>الأضافة :</strong>
                                                                        <span class="badge badge-secondary">{{ $variant->variant  }}</span>
                                                                    </div>
                                                                    <div class="line">
                                                                        <strong>السعر :</strong>
                                                                        <span class="badge badge-secondary">{{ $variant->price  }}</span>
                                                                    </div>
                                                                    <div class="line">
                                                                        <strong>الكمية :</strong>
                                                                        <span class="badge badge-secondary">{{ $variant->qty  }}</span>
                                                                    </div>
                                                                    @if($variant->qty > 1)
                                                                        <div class="line">
                                                                            <strong>السعر الكلى :</strong>
                                                                            <span class="badge badge-secondary">{{ $variant->total_price  }}</span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td><strong>{{ $value->pluck('qty')->sum() }}</strong></td>
                                            <td><strong>{{ $value->pluck('total_price')->sum() }}</strong></td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line text-center">
                                        <strong>السعر الكلى بدون اضافات</strong></td>
                                    @if(isset($order->order_details->groupBy('variant_type')['extra']))
                                    <td class="thick-line"><strong>{{  ($order->grand_total  - $order->shipping -  $order->order_details->groupBy('variant_type')['extra']->pluck('total_price')->sum()) + $order->total_discount }}</strong></td>
                                    @else
                                    <td class="thick-line"><strong>{{ ( $order->grand_total - $order->shipping) + $order->total_discount  }}</strong></td>
                                    @endif
                                </tr>
                                @if(isset($order->order_details->groupBy('variant_type')['extra']))
                                    <tr>
                                        <td class="thick-line"></td>
                                        <td class="thick-line"></td>
                                        <td class="thick-line text-center"> <strong>السعر الكلى للأضافات</strong></td>
                                        <td class="thick-line"><strong>{{ $order->order_details->groupBy('variant_type')['extra']->pluck('total_price')->sum() }}</strong></td>
                                    </tr>
                                @endif
                                @if($order->shipping)
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>الشحن</strong></td>
                                        <td class="no-line"><strong>{{ $order->shipping }}</strong></td>
                                    </tr>
                                @endif
                                @if($order->total_discount)
                                    <tr>
                                        <td class="no-line"></td>
                                        <td class="no-line"></td>
                                        <td class="no-line text-center">
                                            <strong>الخصم</strong></td>
                                        <td class="no-line"><strong>{{ $order->total_discount }}</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="no-line"></td>
                                    <td class="no-line"></td>
                                    <td class="no-line text-center">
                                        <strong>السعر النهائى</strong></td>
                                    <td class="no-line">
                                        <h4 class="m-0"><strong>{{ $order->grand_total }}</strong></h4></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
