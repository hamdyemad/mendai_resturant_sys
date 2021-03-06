@extends('layouts.master')

@section('title')
{{ translate('create new order') }}
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('title') {{ translate('orders') }} @endslot
        @slot('li1') {{ translate('dashboard') }} @endslot
        @slot('li2') {{ translate('orders') }} @endslot
        @slot('route1') {{ route('dashboard') }} @endslot
        @slot('route2') {{ route('orders.index') }} @endslot
        @slot('li3') {{ translate('create new order') }} @endslot
    @endcomponent
    <div class="create_order">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    {{ translate('create new order') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="country">{{ translate('currency') }}</label>
                                    <select class="form-control select2 currency_select" name="currency_id">
                                        @foreach ($currencies as $currency)
                                        <option data-code="{{ $currency->code }}" value="{{ $currency->id }}" @if(old('currency_id') == $currency->id) selected @endif>{{ $currency->code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(Auth::user()->type == 'admin')
                                <div class="col-12 col-md-6 branch_col">
                                    <div class="form-group">
                                        <label for="branch_id">{{ translate('order branch creation') }}</label>
                                        <select class="form-control select2 branch_select" name="branch_id">
                                            @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @if(old('branch_id') == $branch->id) selected @endif>{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }}">
                            @endif
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="type">{{ translate('order type') }}</label>
                                    <select class="form-control order_type select2" name="type">
                                        <option value="inhouse" @if(old('type') == 'inhouse') selected @endif>{{ translate('receipt from the branch') }}</option>
                                        <option value="online" @if(old('type') == 'online') selected @endif>{{ translate('online order') }}</option>
                                    </select>
                                </div>
                            </div>
                            @if(old('type') == 'online')
                                <div class="col-12 col-md-6 country_col">
                                    <div class="form-group">
                                        <label for="country">{{ translate('country') }}</label>
                                        <select class="form-control select2 select_country" name="country_id">
                                            @foreach ($countries as $country)
                                            <option value="{{ $country->id }}" @if(old('country_id') == $country->id) selected @endif>{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 address_col">
                                    <div class="form-group">
                                        <label for="customer_address">{{ translate('customer address') }}</label>
                                        <input type="text" class="form-control" name="customer_address" value="{{ old('customer_address') }}">
                                        @error('customer_address')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="customer_name">{{ translate('customer name') }}</label>
                                    <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name') }}">
                                    @error('customer_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md">
                                <div class="form-group">
                                    <label for="customer_phone">{{ translate('customer phone') }}</label>
                                    <input type="number" class="form-control" name="customer_phone" value="{{ old('customer_phone') }}">
                                    @error('customer_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="products">{{ translate('products') }}</label>
                                    <select class="form-control select_products select2 select2-multiple"data-placeholder="{{ translate('choose') }}" name="products_search[]" multiple></select>
                                    @error("products_search")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes">{{ translate('notes') }}</label>
                                    <textarea id="textarea" class="form-control" name="notes" maxlength="225"
                                        rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="responsive-table products_table"></div>
                            </div>
                            <div class="w-100 cart-of-total-container d-none">
                                <div class="cart-of-total">
                                    <h5>{{ translate('summary') }}</h5>
                                    <div class="responsive-table">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>{{ translate('total price') }}</td>
                                                    <td class="d-flex">
                                                        <div class="total_prices">0</div>
                                                        <div class="currency"></div>
                                                    </td>
                                                </tr>
                                                <tr class="shipping_tr d-none">
                                                    <td>{{ translate('shipping') }}</td>
                                                    <td class="d-flex">
                                                        <div class="shipping">0</div>
                                                        <div class="currency"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('discount') }}</td>
                                                    <td><input class="form-control total_discount" name="total_discount" type="number" placeholder="{{ translate('discount') }}" value="{{ old('total_discount') }}" min="0"></td>
                                                </tr>
                                                <tr>
                                                    <td>{{ translate('price after discount') }}</td>
                                                    <td class="d-flex">
                                                        <div class="grand_total"></div>
                                                        <div class="currency"></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for=""></label>
                                    <input type="submit" value="{{ translate('create') }}" class="btn btn-success">
                                    <a href="{{ route('orders.index') }}" class="btn btn-info">{{ translate('back to orders') }}</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerScript')
    <script>
        let address_col = `
            <div class="col-12 col-md-6 address_col">
                <div class="form-group">
                    <label for="customer_address">{{ translate('customer address') }}</label>
                    <input type="text" class="form-control" name="customer_address" value="{{ old('customer_address') }}">
                    @error('customer_address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            `,
            country_col = `
            <div class="col-12 col-md-6 country_col">
                <div class="form-group">
                    <label for="country">{{ translate('country') }}</label>
                    <select class="form-control select_country" name="country_id"></select>
                </div>
            </div>
            `,
            city_col = `
            <div class="col-12 col-md-6 city_col">
                <div class="form-group">
                    <label for="city_id">{{ translate('city') }}</label>
                    <select class="form-control select_city" name="city_id"></select>
                    @error('city_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            `;
    $(".order_type").on('change', function() {
        arrayOfValues = $(this).val();
        if (arrayOfValues.includes('online')) {
            $(this).parent().parent().after(address_col);
            $(this).parent().parent().after(country_col);
            $(".select_country").select2();
            @foreach ($countries as $country)
                $(".select_country").append(`<option value="{{ $country->id }}" @if(old('country_id') == $country->id) selected @endif>{{ $country->name }}</option>`);
            @endforeach
            getCitiesByCountryId();
        } else {
            $('.shipping_tr').addClass('d-none');
            $(".address_col").remove();
            $(".country_col").remove();
            $(".city_col").remove();
            if($('.shipping_tr').hasClass('d-none')) {
                $(".shipping_tr .shipping").text(0);
            }
        }
        getFullPrice();
    })

    $(".currency").text($("[name=currency_id]").find(":selected").data('code'));

    $(".branch_select,.currency_select").on('change', function() {
        // Change Currency Code
        $(".currency").text($("[name=currency_id]").find(":selected").data('code'));
        // Get Products By Branch id
        getProductsByBranchId($(".branch_select").val());
        // Get Cities By Country id
        getCitiesByCountryIdAjax($(".select_country").val());
        $('.cart-of-total-container').addClass('d-none');
        $('.cart-of-total-container').removeClass('d-block d-md-flex flex-row-reverse ');
        $(".products_table").empty();
        $(".select_products").empty();

        getFullPrice();
    });

    function getProductsByBranchId(branch_id) {
        let token = $("meta[name=_token]").attr('content');
        $.ajax({
            'method': 'POST',
            'data': {
                '_token': token,
                'branch_id': branch_id
            },
            'url': "{{ route('products.all') }}",
            'success': function(res) {
                if(res.status) {
                    $(".select_products").select2().html('');
                    res.data.forEach((obj) => {
                        $(".select_products").append(`
                            <option value="${obj.id}">${obj.name}</option>
                        `);
                    });

                } else {
                    toastr.error(res.message);
                }
            },
            'erorr' : function(err) {
                console.log(err);
            }
        });
    }
    getProductsByBranchId($("[name=branch_id]").val());

    function getCitiesByCountryIdAjax(country_id) {
        let token = $("meta[name=_token]").attr('content');
        $.ajax({
            'method': 'POST',
            'data': {
                '_token': token,
                country_id: country_id,
                currency_id: $("[name=currency_id]").val()
            },
            'url' : `{{ route('countries.cities.all') }}`,
            'success': function(res) {
                if(res.status) {
                    $(".select_city").select2().html('');
                    res.data.forEach((obj) => {
                        $(".select_city").append(`<option value="${obj.id}" data-shipping="${obj.current_price.price}">${obj.name}</option>`);
                    });
                    $('.shipping_tr').removeClass('d-none');
                    $(".shipping_tr .shipping").text($(".select_city option:selected").data('shipping'))
                    $(".select_city").on('change', function() {
                        $(".shipping_tr .shipping").text($(".select_city option:selected").data('shipping'))
                        getFullPrice();
                    })
                    getFullPrice();
                }
            },
            'erorr' : function(err) {
                console.log(err);
            }
        });
    }


    // Get Cities By Country id
    function getCitiesByCountryId() {
        let country_id = $('.select_country option:selected').val();
        if(country_id) {
            $('.select_country').parent().parent().after(city_col);
            getCitiesByCountryIdAjax(country_id);
        }
        $(".select_country").on('change', function() {
            country_id = $(this).val();
            getCitiesByCountryIdAjax(country_id);
        });
    }
    getCitiesByCountryId();

    function getTrOfProductVariantTable(product,obj) {
        let photo = '';
        if(product.photos) {
            photo = ` <img src="{{ asset('${JSON.parse(product.photos)[0]}') }}" alt="">`;
        } else {
            photo = `<img src="{{ asset('/images/product_avatar.png') }}" alt="">`;
        }
        return `<tr id="${obj.id}">
                <td>
                    <div class="d-flex align-items-center">
                        ${photo}
                        <span> ${product.name}</span>
                    </div>
                </td>
                <td>
                    ${obj.variant }
                </td>
                <td>
                    <div class="price">${obj.currenct_price_of_variant.price_after_discount }</div>
                </td>
                <td>
                    <input class="form-control amount" name="products[${product.id}][variants][${obj.id}][amount]"  min="1"  type="number" placeholder="{{ translate('quantity') }}" value="1">
                    @error("products.*.variants.*.amount")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </td>

                <td>
                    <div class="total_price">${obj.currenct_price_of_variant.price_after_discount }</div>
                </td>
            </tr>
        `;
    }

    function getProductVariantTable(variant) {
        if(variant == 'size')  {
            return `
            <table class="table size-table">
                <thead>
                    <th>{{ translate('food name') }}</th>
                    <th>{{ translate('sizes') }}</th>
                    <th>{{ translate('price') }}</th>
                    <th>{{ translate('quantity') }}</th>
                    <th>{{ translate('total price') }}</th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                </tbody>
            </table>
            `;
        } else if(variant == 'extra') {
            return `
            <table class="table extra-table">
                <thead>
                    <th>{{ translate('food name') }}</th>
                    <th>{{ translate('extras') }}</th>
                    <th>{{ translate('price') }}</th>
                    <th>{{ translate('quantity') }}</th>
                    <th>{{ translate('total price') }}</th>
                    <th></th>
                    <th></th>
                </thead>
                <tbody>
                </tbody>
            </table>
            `;
        }
    }

    function getProductVariantHeadingTr(product) {
        let photo = '';
        if(product.photos) {
            photo = ` <img src="{{ asset('${JSON.parse(product.photos)[0]}') }}" alt="">`;
        } else {
            photo = `<img src="{{ asset('/images/product_avatar.png') }}" alt="">`;
        }

        return `
            <tr class="${product.id}">
                <input type="hidden" value="products[${product.id}]">
                <td>
                    <div class="d-flex align-items-center">
                        ${photo}
                        <span>${product.name}</span>
                    </div>
                </td>
            </tr>
        `;
    }

    function getProductVariantHeadingTable() {
        return `
        <div class="table-responsive">
            <table class="table variant_table">
                <thead>
                    <th>{{ translate('food name') }}</th>
                    <th>{{ translate('price') }}</th>
                    <th>{{ translate('quantity') }}</th>
                    <th>{{ translate('total price') }}</th>
                    <th>{{ translate('size') }}</th>
                    <th>{{ translate('extra') }}</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        `;
    }


    if($(".select_products").val().length !== 0) {
        $('.cart-of-total-container').removeClass('d-none');
        $('.cart-of-total-container').addClass('d-block d-md-flex flex-row-reverse ');
        getProductsWithAjax($(".select_products").val());
    }

    function getProductsWithAjax(productsIds) {
        $.ajax({
            'method': 'GET',
            'data': {
                ids: productsIds,
                currency_id: $("[name=currency_id]").val()
            },
            'url' : "{{ route('products.all_by_ids') }}",
            'success': function(products) {
                if(products.length !== 0) {
                    $(".products_table").empty();
                    products.forEach(product => {
                        if(product.variants.length !==0) {
                            if($(".products_table").find('.variant_table').length == 0) {
                                $(".products_table").append(getProductVariantHeadingTable());
                            }
                            $(".products_table .variant_table tbody").append(getProductVariantHeadingTr(product))
                            let extraTypeArray = product.variants.filter((obj) => {
                                return obj.type == 'extra';
                            });
                            let sizeTypeArray = product.variants.filter((obj) => {
                                return obj.type == 'size';
                            });
                            if(sizeTypeArray.length !==0) {
                                $(`.${product.id}`).append(`<td>{{ translate('there is no price') }}</td>`);
                                $(`.${product.id}`).append(`<td>{{ translate('there is no quantity') }}</td>`);
                                $(`.${product.id}`).append(`<td>{{ translate('there is no total price') }}</td>`);
                                $(`.${product.id}`).append(`
                                    <td><ul class="select_variant size_select"></ul></td>
                                `);
                                sizeTypeArray.forEach((size) => {
                                    $(`.${product.id} .size_select`).append(`
                                        <li class="variant" data-variant="${size.type}" data-variant_value='${JSON.stringify(size)}' data-product_value='${JSON.stringify(product)}'>
                                            ${size.variant}
                                        </li>
                                    `);
                                });
                            } else {
                                $(`.${product.id}`).append(`<td><div class="price">${product.currenct_price.price_after_discount}</div></td>`);
                                $(`.${product.id}`).append(`<td><input class="form-control amount" value="1" min="1" type="number" name="products[${product.id}][amount]"></td>`);
                                $(`.${product.id}`).append(`<td><div class="total_price">${product.currenct_price.price_after_discount}</div></td>`);
                                $(`.${product.id}`).append(`<td>{{ translate('there is no sizes') }}</td>`);
                            }
                            if(extraTypeArray.length !==0) {
                                $(`.${product.id}`).append(`
                                    <td><ul class="select_variant extra_select"></ul></td>
                                `);
                                extraTypeArray.forEach((extra) => {
                                    $(`.${product.id} .extra_select`).append(`
                                        <li class="variant" data-variant="${extra.type}" data-variant_value='${JSON.stringify(extra)}' data-product_value='${JSON.stringify(product)}'>
                                            ${extra.variant}
                                        </li>
                                    `);
                                });
                            } else {
                                $(`.${product.id}`).append(`<td>{{ translate('there is no extras') }}</td>`);
                            }

                        } else {
                            if($(".products_table").find('.variant_table').length == 0) {
                                $(".products_table").append(getProductVariantHeadingTable());
                            }
                            $(".products_table .variant_table tbody").append(getProductVariantHeadingTr(product))
                            $(`.${product.id}`).append(`<td><div class="price">${product.currenct_price.price_after_discount}</div></td>`);
                            $(`.${product.id}`).append(`<td><input class="form-control amount" value="1" min="1" type="number" name="products[${product.id}][amount]"></td>`);
                            $(`.${product.id}`).append(`<td><div class="total_price">${product.currenct_price.price_after_discount}</div></td>`);
                            $(`.${product.id}`).append(`<td>{{ translate('there is no sizes') }}</td>`);
                            $(`.${product.id}`).append(`<td>{{ translate('there is no extras') }}</td>`);
                            getFullPrice();
                        }
                    });
                    $(".variant").click('click', function() {
                        let product = $(this).data('product_value');
                        $(this).toggleClass("active");
                        let variant = $(this).data('variant');
                        if($(".products_table").find(`.${variant}-table`).length == 0) {
                            $(".products_table").append(getProductVariantTable(variant))
                        }
                        if($(this).hasClass("active")) {
                            $(`.products_table .${variant}-table tbody`).append(getTrOfProductVariantTable(product,$(this).data('variant_value')));
                        } else {
                            $(`.products_table .${variant}-table tbody #${$(this).data('variant_value').id}`).remove();
                        }
                        if($(".products_table").find(`.${variant}-table tbody`).children().length == 0) {
                            $(`.products_table .${variant}-table`).remove();
                        }
                        amountChange();
                        getFullPrice();
                    })
                    getFullPrice();
                    amountChange();
                }
            },
            'error': function(error) {
                console.log(error)
            }
        });

    }

    $(".select_products").on('change', function() {
        arrayOfValues = $(this).val();
        if(arrayOfValues.length !== 0) {
            $('.cart-of-total-container').removeClass('d-none');
            $('.cart-of-total-container').addClass('d-block d-md-flex flex-row-reverse ');
            getProductsWithAjax(arrayOfValues);
        } else {
            $(".products_table").empty();
            $('.cart-of-total-container').removeClass('d-block d-md-flex flex-row-reverse ');
            $('.cart-of-total-container').addClass('d-none');
        }
    });
    function getFullPrice() {
        let prices = [],
            total_prices = $(".total_prices"),
            grandTotal = $(".grand_total"),
            shippping = parseFloat($(".shipping").text()),
            total_discount = $('.total_discount');
        if(isNaN(shippping)) {
            shippping = 0;
        }
        if($(".variant_table tbody").children().length !== 0) {
            $(".variant_table tbody").children().each((index, tr) => {
                if(!isNaN(parseFloat($(tr).find('.total_price').text()))) {
                    prices.push(parseFloat($(tr).find('.total_price').text()));
                }
            });
        }

        if($(".variant_table .select_variant").children().length !== 0) {
            $(".variant_table .select_variant").each((index, variant_ul) => {
                $(variant_ul).children().each((index, selected) => {
                    if($(selected).hasClass('active')) {
                        prices.push(parseFloat($(`#${$(selected).data('variant_value').id}`).find('.total_price').text()))
                    }
                });
            });
        }
        if(prices.length !== 0) {
            prices = prices.reduce((acc, current) => acc + current);
        }
        total_prices.text(prices);
        grandTotal.text(prices + shippping);
        total_discount.on('keyup', function() {
            let full_price = (prices +  shippping);
            full_price = full_price - $(this).val();
            grandTotal.text(full_price);
        });
    }
    getFullPrice();
    function amountChange() {
        $(".amount").on('keyup', function() {
            let priceVal = parseFloat($(this).parent().parent().find('.price').text()),
            amountVal = parseFloat($(this).val());
            $(this).parent().parent().find('.total_price').text(priceVal * amountVal);
            getFullPrice();
        });
    }

    </script>
@endsection
