@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.invoices', 1)]))

@section('content')
<!-- Default box -->
<div class="box box-success">
    {!! Form::open(['url' => 'incomes/invoices', 'files' => true, 'role' => 'form']) !!}

    <div class="box-body">
        @stack('customer_id_input_start')
        <div class="form-group col-md-6 required {{ $errors->has('customer_id') ? 'has-error' : ''}}">
            {!! Form::label('customer_id', trans_choice('general.customers', 1), ['class' => 'control-label']) !!}
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                {!! Form::select('customer_id', $customers, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)])])) !!}
                <div class="input-group-btn">
                    <button type="button" id="button-customer" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! $errors->first('customer_id', '<p class="help-block">:message</p>') !!}
        </div>
        @stack('customer_id_input_end')

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange', $currencies, setting('general.default_currency')) }}

        {{ Form::textGroup('invoiced_at', trans('invoices.invoice_date'), 'calendar',['id' => 'invoiced_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy/mm/dd\'', 'data-mask' => '', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

        {{ Form::textGroup('due_at', trans('invoices.due_date'), 'calendar',['id' => 'due_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy/mm/dd\'', 'data-mask' => '', 'autocomplete' => 'off']) }}

        {{ Form::textGroup('invoice_number', trans('invoices.invoice_number'), 'file-text-o', ['required' => 'required'], $number) }}

        {{ Form::textGroup('order_number', trans('invoices.order_number'), 'shopping-cart', []) }}

        <div class="form-group col-md-12">
            {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}
            <div class="table-responsive">
                <table class="table table-bordered" id="items">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            @stack('actions_th_start')
                            <th width="5%"  class="text-center">{{ trans('general.actions') }}</th>
                            @stack('actions_th_end')
                            @stack('name_th_start')
                            <th width="40%" class="text-left">{{ trans('general.name') }}</th>
                            @stack('name_th_end')
                            @stack('quantity_th_start')
                            <th width="5%" class="text-center">{{ trans('invoices.quantity') }}</th>
                            @stack('quantity_th_end')
                            @stack('price_th_start')
                            <th width="10%" class="text-right">{{ trans('invoices.price') }}</th>
                            @stack('price_th_end')
                            @stack('taxes_th_start')
                            <th width="15%" class="text-right">{{ trans_choice('general.taxes', 1) }}</th>
                            @stack('taxes_th_end')
                            @stack('total_th_start')
                            <th width="10%" class="text-right">{{ trans('invoices.total') }}</th>
                            @stack('total_th_end')
                        </tr>
                    </thead>
                    <tbody>
                        <?php $item_row = 0; ?>
                        @include('incomes.invoices.item')
                        <?php $item_row++; ?>
                        @stack('add_item_td_start')
                        <tr id="addItem">
                            <td class="text-center"><button type="button" id="button-add-item" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                            <td class="text-right" colspan="5"></td>
                        </tr>
                        @stack('add_item_td_end')
                        @stack('sub_total_td_start')
                        <tr>
                            <td class="text-right" colspan="5"><strong>{{ trans('invoices.sub_total') }}</strong></td>
                            <td class="text-right"><span id="sub-total">0</span></td>
                        </tr>
                        @stack('sub_total_td_end')
                        @stack('add_discount_td_start')
                        <tr>
                            <td class="text-right" style="vertical-align: middle;" colspan="5">
                                <a href="javascript:void(0)" id="discount-text" rel="popover">{{ trans('invoices.add_discount') }}</a>
                            </td>
                            <td class="text-right">
                                <span id="discount-total"></span>
                                {!! Form::hidden('discount', null, ['id' => 'discount', 'class' => 'form-control text-right']) !!}
                            </td>
                        </tr>
                        @stack('add_discount_td_end')
                        @stack('tax_total_td_start')
                        <tr>
                            <td class="text-right" colspan="5"><strong>{{ trans_choice('general.taxes', 1) }}</strong></td>
                            <td class="text-right"><span id="tax-total">0</span></td>
                        </tr>
                        @stack('tax_total_td_end')
                        @stack('grand_total_td_start')
                        <tr>
                            <td class="text-right" colspan="5"><strong>{{ trans('invoices.total') }}</strong></td>
                            <td class="text-right"><span id="grand-total">0</span></td>
                        </tr>
                        @stack('grand_total_td_end')
                    </tbody>
                </table>
            </div>
        </div>

        {{ Form::textareaGroup('notes', trans_choice('general.notes', 2)) }}

        @stack('category_id_input_start')
        <div class="form-group col-md-6 required {{ $errors->has('category_id') ? 'has-error' : ''}}">
            {!! Form::label('category_id', trans_choice('general.categories', 1), ['class' => 'control-label']) !!}
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-folder-open-o"></i></div>
                {!! Form::select('category_id', $categories, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)])])) !!}
                <div class="input-group-btn">
                    <button type="button" id="button-category" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
        </div>
        @stack('category_id_input_end')

        {{ Form::recurring('create') }}

        {{ Form::fileGroup('attachment', trans('general.attachment')) }}

        {{ Form::hidden('customer_name', '', ['id' => 'customer_name']) }}
        {{ Form::hidden('customer_email', '', ['id' => 'customer_email']) }}
        {{ Form::hidden('customer_tax_number', '', ['id' => 'customer_tax_number']) }}
        {{ Form::hidden('customer_phone', '', ['id' => 'customer_phone']) }}
        {{ Form::hidden('customer_address', '', ['id' => 'customer_address']) }}
        {{ Form::hidden('currency_rate', '', ['id' => 'currency_rate']) }}
        {{ Form::hidden('invoice_status_code', 'draft', ['id' => 'invoice_status_code']) }}
        {{ Form::hidden('amount', '0', ['id' => 'amount']) }}
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        {{ Form::saveButtons('incomes/invoices') }}
    </div>
    <!-- /.box-footer -->

    {!! Form::close() !!}
</div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var item_row = '{{ $item_row }}';

        $(document).on('click', '#button-add-item', function (e) {
            var currency_code = $('#currency_code').val();

            $.ajax({
                url: '{{ url("incomes/invoices/addItem") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {item_row: item_row, currency_code : currency_code},
                success: function(json) {
                    if (json['success']) {
                        $('#items tbody #addItem').before(json['html']);
                        //$('[rel=tooltip]').tooltip();

                        $('[data-toggle="tooltip"]').tooltip('hide');

                        $('#item-row-' + item_row + ' .tax-select2').select2({
                            placeholder: {
                                id: '-1', // the value of the option
                                text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                            }
                        });

                        var currency = json['data']['currency'];

                        $("#item-price-" + item_row).maskMoney({
                            thousands : currency.thousands_separator,
                            decimal : currency.decimal_mark,
                            precision : currency.precision,
                            allowZero : true,
                            prefix : (currency.symbol_first) ? currency.symbol : '',
                            suffix : (currency.symbol_first) ? '' : currency.symbol
                        });

                        $("#item-price-" + item_row).trigger('focusout');

                        item_row++;
                    }
                }
            });
        });

        $(document).ready(function(){
            $(".input-price").maskMoney({
                thousands : '{{ $currency->thousands_separator }}',
                decimal : '{{ $currency->decimal_mark }}',
                precision : {{ $currency->precision }},
                allowZero : true,
                @if($currency->symbol_first)
                prefix : '{{ $currency->symbol }}'
                @else
                suffix : '{{ $currency->symbol }}'
                @endif
            });

            $('.input-price').trigger('focusout');

            //Date picker
            $('#invoiced_at').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            //Date picker
            $('#due_at').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $(".tax-select2").select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                }
            });

            $("#customer_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)]) }}"
            });

            $("#currency_code").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $('#attachment').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                placeholder : '{{ trans('general.form.no_file_selected') }}'
            });

            var autocomplete_path = "{{ url('common/items/autocomplete') }}";

            $(document).on('click', '.form-control.typeahead', function() {
                input_id = $(this).attr('id').split('-');

                item_id = parseInt(input_id[input_id.length-1]);

                $(this).typeahead({
                    minLength: 3,
                    displayText:function (data) {
                        return data.name + ' (' + data.sku + ')';
                    },
                    source: function (query, process) {
                        $.ajax({
                            url: autocomplete_path,
                            type: 'GET',
                            dataType: 'JSON',
                            data: 'query=' + query + '&type=invoice&currency_code=' + $('#currency_code').val(),
                            success: function(data) {
                                return process(data);
                            }
                        });
                    },
                    afterSelect: function (data) {
                        $('#item-id-' + item_id).val(data.item_id);
                        $('#item-quantity-' + item_id).val('1');
                        $('#item-price-' + item_id).val(data.sale_price);
                        $('#item-tax-' + item_id).val(data.tax_id);

                        // This event Select2 Stylesheet
                        $('#item-price-' + item_id).trigger('focusout');
                        $('#item-tax-' + item_id).trigger('change');

                        $('#item-total-' + item_id).html(data.total);

                        totalItem();
                    }
                });
            });

            $('a[rel=popover]').popover({
                html: true,
                placement: 'bottom',
                title: '{{ trans('invoices.discount') }}',
                content: function () {
                    html  = '<div class="discount box-body">';
                    html += '    <div class="col-md-6">';
                    html += '        <div class="input-group" id="input-discount">';
                    html += '            {!! Form::number('pre-discount', null, ['id' => 'pre-discount', 'class' => 'form-control text-right']) !!}';
                    html += '            <div class="input-group-addon"><i class="fa fa-percent"></i></div>';
                    html += '        </div>';
                    html += '    </div>';
                    html += '    <div class="col-md-6">';
                    html += '        <div class="discount-description">';
                    html += '           {{ trans('invoices.discount_desc') }}';
                    html += '        </div>';
                    html += '    </div>';
                    html += '</div>';
                    html += '<div class="discount box-footer">';
                    html += '    <div class="col-md-12">';
                    html += '        <div class="form-group no-margin">';
                    html += '            {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' => 'save-discount','class' => 'btn btn-success']) !!}';
                    html += '            <a href="javascript:void(0)" id="cancel-discount" class="btn btn-default"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</a>';
                    html += '       </div>';
                    html += '    </div>';
                    html += '</div>';

                    return html;
                }
            });

            $(document).on('keyup', '#pre-discount', function(e){
                e.preventDefault();

                $('#discount').val($(this).val());

                totalItem();
            });

            $(document).on('click', '#save-discount', function(){
                $('a[rel=popover]').trigger('click');
            });

            $(document).on('click', '#cancel-discount', function(){
                $('#discount').val('');

                totalItem();

                $('a[rel=popover]').trigger('click');
            });

            $(document).on('change', '#currency_code, #items tbody select', function(){
                totalItem();
            });

            $(document).on('keyup', '#items tbody .form-control', function(){
                totalItem();
            });

            $(document).on('change', '#customer_id', function (e) {
                $.ajax({
                    url: '{{ url("incomes/customers/currency") }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: 'customer_id=' + $(this).val(),
                    success: function(data) {
                        $('#customer_name').val(data.name);
                        $('#customer_email').val(data.email);
                        $('#customer_tax_number').val(data.tax_number);
                        $('#customer_phone').val(data.phone);
                        $('#customer_address').val(data.address);

                        $('#currency_code').val(data.currency_code);
                        $('#currency_rate').val(data.currency_rate);

                        $('.input-price').each(function(){
                            input_price_id = $(this).attr('id');
                            input_currency_id = input_price_id.replace('price', 'currency');

                            $('#' + input_currency_id).val(data.currency_code);

                            amount = $(this).maskMoney('unmasked')[0];

                            $(this).maskMoney({
                                thousands : data.thousands_separator,
                                decimal : data.decimal_mark,
                                precision : data.precision,
                                allowZero : true,
                                prefix : (data.symbol_first) ? data.symbol : '',
                                suffix : (data.symbol_first) ? '' : data.symbol
                            });

                            $(this).val(amount);

                            $(this).trigger('focusout');
                        });

                        // This event Select2 Stylesheet
                        $('#currency_code').trigger('change');
                    }
                });
            });
        });

        function totalItem() {
            $.ajax({
                url: '{{ url("common/items/totalItem") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#currency_code, #discount input[type=\'number\'], #items input[type=\'text\'],#items input[type=\'number\'],#items input[type=\'hidden\'], #items textarea, #items select'),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (data) {
                        $.each( data.items, function( key, value ) {
                            $('#item-total-' + key).html(value);
                        });

                        $('#discount-text').text(data.discount_text);

                        $('#sub-total').html(data.sub_total);
                        $('#discount-total').html(data.discount_total);
                        $('#tax-total').html(data.tax_total);
                        $('#grand-total').html(data.grand_total);

                        $('.input-price').each(function(){
                            input_price_id = $(this).attr('id');
                            input_currency_id = input_price_id.replace('price', 'currency');

                            $('#' + input_currency_id).val(data.currency_code);

                            amount = $(this).maskMoney('unmasked')[0];

                            $(this).maskMoney({
                                thousands : data.thousands_separator,
                                decimal : data.decimal_mark,
                                precision : data.precision,
                                allowZero : true,
                                prefix : (data.symbol_first) ? data.symbol : '',
                                suffix : (data.symbol_first) ? '' : data.symbol
                            });

                            $(this).val(amount);

                            $(this).trigger('focusout');
                        });
                    }
                }
            });
        }

        $(document).on('click', '#button-customer', function (e) {
            $('#modal-create-customer').remove();

            $.ajax({
                url: '{{ url("modals/customers/create") }}',
                type: 'GET',
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('body').append(json['html']);
                    }
                }
            });
        });

        $(document).on('click', '#button-category', function (e) {
            $('#modal-create-category').remove();

            $.ajax({
                url: '{{ url("modals/categories/create") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {type: 'income'},
                success: function(json) {
                    if (json['success']) {
                        $('body').append(json['html']);
                    }
                }
            });
        });
    </script>
@endpush
