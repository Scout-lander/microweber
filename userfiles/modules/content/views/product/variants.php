<?php
$productVariantOptions = [];
$productVariantOptions[] = [
    'option_name'=>'Size',
    'option_values'=>['L','X','XL','M'],
];
$productVariantOptions[] = [
    'option_name'=>'Color',
    'option_values'=>['Green','Blue','White','Black'],
];
?>

<script>mw.lib.require('mwui_init')</script>
<style>
    .js-product-variants {
        display: none;
    }
</style>

<script>
    function addProductVariantValues(variant_name) {
        var variantHtml = '<tr>\n' +
            '<th scope="row" style="vertical-align: middle;">\n' +
            '    <span>'+variant_name+'</span>\n' +
            '</th>\n' +
            '<td>\n' +
            '    <div class="input-group prepend-transparent m-0">\n' +
            '        <div class="input-group-prepend">\n' +
            '            <span class="input-group-text text-muted">BGN</span>\n' +
            '        </div>\n' +
            '        <input type="text" class="form-control" value="0.00">\n' +
            '    </div>\n' +
            '</td>\n' +
            '<td>\n' +
            '    <div class="input-group append-transparent input-group-quantity m-0">\n' +
            '        <input type="text" class="form-control" value="0">\n' +
            '        <div class="input-group-append">\n' +
            '            <div class="input-group-text plus-minus-holder">\n' +
            '                <button type="button" class="plus"><i class="mdi mdi-menu-up"></i></button>\n' +
            '                <button type="button" class="minus"><i class="mdi mdi-menu-down"></i></button>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</td>\n' +
            '<td>\n' +
            '    <div class="form-group m-0">\n' +
            '        <input type="text" class="form-control" value="">\n' +
            '    </div>\n' +
            '</td>\n' +
            '<td style="vertical-align: middle;">\n' +
            '    <div class="btn-group">\n' +
            '        <button class="btn btn-outline-secondary btn-sm">Edit</button>\n' +
            '        <button class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-trash-can-outline"></i></button>\n' +
            '    </div>\n' +
            '</td>\n' +
            '</tr>';

        $('.js-product-variants-fields').append(variantHtml);
    }

    function addProductVariantOption(option_id = 0, option_name = '', option_values = '')
    {
        var optionHtml = '<div class="row js-product-variant-option-box js-product-variant-option-'+option_id+'">\n' +
            '<div class="col-md-4">\n' +
            '<div class="form-group">\n' +
            '     <h6 class="pb-1"><strong>Option</strong></h6>\n' +
            '      <div>\n' +
            '           <input type="text" name="product_variant_option['+option_id+'][name]" value="'+option_name+'" class="form-control js-option-name">\n' +
            '     </div>\n' +
            '</div>\n' +
            '</div>\n' +
            '<div class="col-md-8">\n' +
            '    <div class="text-right">\n' +
            '        <button type="button" class="btn btn-link py-1 pb-2 h-auto px-2">Edit</button>\n' +
            '        <button type="button" class="btn btn-link btn-link-danger py-1 pb-2 h-auto px-2" onclick="deleteProductVariantOption('+option_id+')">Remove</button>\n' +
            '    </div>\n' +
            '    <div class="form-group">\n' +
            '        <input type="text" data-role="tagsinput"  name="product_variant_option['+option_id+'][values]" value="'+option_values+'" class="js-tags-input" placeholder="Separate options with a comma" />\n' +
            '    </div>\n' +
            '</div>\n' +
            '</div>';

        $('.js-product-variants-options').append(optionHtml);

        $("input[name='product_variant_option["+option_id+"][values]']").tagsinput()
    }


    const moveThreadForwardAt = (t, tCursor) => {
        if (tCursor < 0)
            return true; // reached end of first array

        const newIndex = (t[tCursor][0] + 1) % t[tCursor][1];
        t[tCursor][0] = newIndex;

        if (newIndex == 0)
            return moveThreadForwardAt(t, tCursor - 1);

        return false;
    }

    const cartesianMult = (...args) => {
        let result = [];
        const t = Array.from(Array(args.length)).map((x, i) => [0, args[i].length]);
        let reachedEndOfFirstArray = false;

        while (false == reachedEndOfFirstArray) {
            result.push(t.map((v, i) => args[i][v[0]]));

            reachedEndOfFirstArray = moveThreadForwardAt(t, args.length - 1);
        }

        return result;
    }

    function refreshProductVariantValues()
    {
        const productVariantCombinations = [];

        $(".js-product-variant-option-box").each(function() {
            //var productVariantOptionName = $(this).find('.js-option-name').val();
            var productVariantOptionValues = $(this).find('.js-tags-input').val().split(",");
            productVariantCombinations.push(productVariantOptionValues);
        });

        for (let item of cartesianMult(productVariantCombinations)) {
            console.log('[' + item.join(', ') + ']');
            addProductVariantValues(item.join('/'));
        }
    }

    <?php
    foreach ($productVariantOptions as $productVariantOptionKey=>$productVariantOption):
    ?>
        addProductVariantOption(<?php echo $productVariantOptionKey; ?>, '<?php echo $productVariantOption['option_name']; ?>', '<?php echo implode(',', $productVariantOption['option_values']); ?>,');
    <?php
    endforeach;
    ?>

    function deleteProductVariantOption(option_id) {
        $('.js-product-variant-option-' + option_id).remove();
    }

    $(document).ready(function () {

       $('.js-product-has-variants').click(function () {
           $('.js-product-variants').toggle();
       });

       $('.js-add-variant-option').click(function () {
           if ($('.js-product-variant-option-box').length > 2) {
                alert('Maximum product variants are 3');
               return;
           }
           refreshProductVariantValues();
           addProductVariantOption(Math.floor(Math.random() * 1000));

       });

        <?php if (!empty($productVariantOptions)): ?>
        $('.js-product-has-variants').click();
        <?php endif; ?>

    });
</script>

<div class="card style-1 mb-3">
    <div class="card-header no-border">
        <h6><strong>Variants</strong></h6>
    </div>

    <div class="card-body pt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-product-has-variants" id="the-product-has-variants">
                        <label class="custom-control-label" for="the-product-has-variants">This product has multiple options, like different sizes or colors</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-product-variants">
            <hr class="thin no-padding"/>

            <h6 class="text-uppercase mb-3"><strong>Create an option</strong></h6>

            <div class="options js-product-variants-options"></div>

            <hr class="thin" />

            <button type="button" class="btn btn-outline-primary text-dark js-add-variant-option">Add another option</button>
            <hr class="thin no-padding"/>
            <h6 class="text-uppercase mb-3"><strong>Preview</strong></h6>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" class="border-0">Variant</th>
                        <th scope="col" class="border-0">Price</th>
                        <th scope="col" class="border-0">Quantity</th>
                        <th scope="col" class="border-0">SKU</th>
                        <th scope="col" class="border-0">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="js-product-variants-fields"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>