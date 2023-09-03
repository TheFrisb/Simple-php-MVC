

$(document).ready(function (){
    console.log('Jquery loaded!');

    const body = $('body');
    const overlay = $("#overlay");
    const cartIcon = $("#cartIcon");
    const cart = $("#cart");
    const cartTotalHolder = $("#cartTotal");
    const checkoutTotalHolder = $("#checkoutTotal");
    const cartQuantityHolder = $("#cartQuantity");
    const cartItemTemplate = $('#cartItemTemplate').clone();
    const checkoutItemTemplate= $("#checkoutItemTemplate").clone();
    const cartContent = $("#cartContent");
    const checkoutContent = $("#checkoutItemsContent");
    const emptyCartText = $("#emptyCartText");
    const cartTotalSection = $("#cartTotalSection") // eh
    const checkoutModal = $("#checkoutModal");
    const checkoutForm = $("#checkoutForm");
    const checkoutAlert = $("#checkoutAlertText");

    function createCartItem(product){
        let newCartItem = cartItemTemplate.clone();
        newCartItem.attr('data-product-id', product.product_id);
        newCartItem.find('.cartItemThumbnail').attr('src', product.thumbnail_path);
        newCartItem.find('.cartItemTitle').text(product.title);
        newCartItem.find('.cartItemQuantity').text(product.quantity);
        newCartItem.find('.cartItemQuantityInput').val(product.quantity);
        newCartItem.find('.cartItemSalePrice').text(product.sale_price); 
        newCartItem.show();
        cartContent.append(newCartItem);
        console.log(newCartItem);
    }

    function createCheckoutItem(product){
        let newCheckoutItem = `<tr class="checkoutItem" data-product-id="${product.id}">
                <td>
                    <span class="checkoutItemQuantity">${product.quantity}</span> x <span class="font-semibold checkoutItemTitle">${product.title}</span>
                </td>
                <td>
                    <span class="checkoutItemSalePrice">${product.sale_price}</span>$
                </td>
            </tr>`
        checkoutContent.append(newCheckoutItem);
    }
    function openCart(){
        overlay.show();
        body.addClass('overlayActive');
        cart.addClass('active')
        overlay.on('click', overlayClickHandler)
    }
    function overlayClickHandler(){
        overlay.hide();
        if(cart.hasClass('active') && !checkoutModal.hasClass('active')){
            cart.removeClass('active');
        }
        if(checkoutModal.hasClass('active')){
            if(!checkoutAlert.hasClass('invisibile')){
                checkoutAlert.addClass('invisible')
            }
            checkoutModal.fadeOut('fast');
            checkoutModal.removeClass('active');
        }
        if($("#newProductModal").length){
            $("#newProductModal").fadeOut('fast');
        }
        body.removeClass('overlayActive')
        overlay.off('click', overlayClickHandler);
    }
    function openCheckoutModal(){
        if(!body.hasClass('overlayActive')){
            overlay.show();
            body.addClass('overlayActive');
            checkoutModal.fadeIn();
            overlay.on('click', overlayClickHandler)
        } else {
            if(cart.hasClass('active')){
                cart.removeClass('active');
            }
            checkoutModal.fadeIn('fast');
            checkoutModal.addClass('active');
        }
    }

    function cartMakeBuyable(cartTotal){
        let checkoutBtn = $("#checkoutBtn");
        checkoutBtn.removeClass('unBuyable closeCart');
        checkoutBtn.text('Checkout')
        cartTotalSection.find('#cartTotal').text(cartTotal);
        cartTotalSection.show();
    }

    function cartMakeUnBuyable(){
        let checkoutBtn = $("#checkoutBtn");
        checkoutBtn.addClass('unBuyable closeCart');
        checkoutBtn.text('Continue shopping')
        cartTotalSection.find('#cartTotal').text(0);
        cartTotalSection.hide();
        cartContent.addClass('unBuyable');
    }

    
    $(document).on('click', '.closeCart', function(){
        overlayClickHandler();
    });
    $(document).on('click', '.closeCheckout', function(){
        overlayClickHandler();
    })
    cartIcon.on("click", function () {
        openCart()
    })


    $(document).on('click', '.addToCartBtn', function(){
        let button = $(this);
        let productCard = button.closest('.product');
        let productId = parseInt(productCard.data('product-id'));
        
        $.ajax({
            url: '/api/add-to-cart',
            method: 'POST',
            data: {
                'productId': productId,
            },
            success: function(response){
                console.log(response);
                let data = response;
                if(emptyCartText.is(':visible')){
                    emptyCartText.hide();
                    cartMakeBuyable(data.cartTotal);
                }
                let checkProduct = $('.cartItem[data-product-id="' + data.product.product_id + '"]');
                if(checkProduct.length){
                    checkProduct.find('.cartItemQuantity').text(data.product.quantity);
                    checkProduct.find('.cartItemQuantityInput').val(data.product.quantity);
                    let checkoutProduct = $('.checkoutItem[data-product-id="' + data.product.id + '"]');
                    checkoutProduct.find('.checkoutItemQuantity').text(data.product.quantity);
                } else {
                    createCartItem(data.product);
                    createCheckoutItem(data.product);
                }
                
                cartQuantityHolder.text(data.totalItems);
                cartTotalHolder.text(data.cartTotal);
                checkoutTotalHolder.text(data.cartTotal);
                openCart();
            }
        })
    })
    
    $(document).on('click', '.removeCartItem', function(){
        let button = $(this);
        let cartItem = button.closest('.cartItem');
        let productId = parseInt(cartItem.data('product-id'));
        console.log(productId)
        $.ajax({
            url: 'api/remove-product',
            method: 'POST',
            data: {
                'productId': productId,
            },
            success: function(response){
                let data = response
                cartItem.remove();
                cartQuantityHolder.text(data.totalItems);
                if(data.totalItems === 0){
                    emptyCartText.show();
                    cartMakeUnBuyable();
                }
                cartTotalHolder.text(data.cartTotal);
                checkoutTotalHolder.text(data.cartTotal);
                $('.checkoutItem[data-product-id="' + data.product.product_id + '"]').remove();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        })
    });

    $(document).on('click', '.cartItemQuantityBtn', function(){
        let button = $(this);
        let cartItem = button.closest('.cartItem');
        let productId = parseInt(cartItem.data('product-id'));
        let quantity = parseInt(button.siblings('.cartItemQuantityInput').val());
        console.log(productId, ' - ', quantity);
        if(button.hasClass('cartItemQuantityMinus')){
            quantity--;
        } else {
            quantity++;
        }
        $.ajax({
            url: 'api/update-product-quantity',
            method: 'POST',
            data: {
                'productId': productId,
                'quantity': quantity
            },
            success: function(response){
                console.log(response);
                let data = response;
                cartQuantityHolder.text(data.totalItems);
                cartTotalHolder.text(data.cartTotal);
                checkoutTotalHolder.text(data.cartTotal);
                let checkoutEl = $('.checkoutItem[data-product-id="' + data.product.product_id + '"]');
                if(data.product.quantity === 0){
                    cartItem.remove();
                    checkoutEl.remove();
                    if(data.totalItems === 0){
                        emptyCartText.show();
                        cartMakeUnBuyable();
                    }
                } else {
                    cartItem.find('.cartItemQuantityInput').val(data.product.quantity);
                    cartItem.find('.cartItemQuantity').text(data.product.quantity);
                    checkoutEl.find('.checkoutItemQuantity').text(data.product.quantity);
                }
                
            }
        })
    });
    $(document).on('change', '.cartItemQuantityInput', function(){
        let input = $(this);
        let cartItem = input.closest('.cartItem');
        let productId = parseInt(cartItem.data('product-id'));
        let quantity = parseInt(input.val());

        $.ajax({
            url: 'api/cart-actions',
            method: 'POST',
            data: {
                'productId': productId,
                'quantity': quantity
            },
            success: function(response){
                console.log(response);
                data = response;
                cartQuantityHolder.text(data.totalItems);
                cartTotalHolder.text(data.cartTotal);
                checkoutTotalHolder.text(data.cartTotal);
                let checkoutEl = $('.checkoutItem[data-product-id="' + data.product.product_id + '"]');
                if(data.product.quantity === 0){
                    cartItem.remove();
                    checkoutEl.remove();
                    if(data.totalItems === 0){
                        emptyCartText.show();
                        cartMakeUnBuyable();
                    }
                } else {
                    cartItem.find('.cartItemQuantityInput').val(data.product.quantity);
                    cartItem.find('.cartItemQuantity').text(data.product.quantity);
                    checkoutEl.find('.checkoutItemQuantity').text(data.product.quantity);
                }
                
            }
        })
    });
    $(document).on('keydown', '.cartItemQuantityInput', function(e){
        let keyCode = e.which;


        if (keyCode < 48 || keyCode > 57) {
            if (keyCode !== 8) {
                e.preventDefault();
            }
        }
    })
    $(document).on('click', '#checkoutBtn', function(){
        let button = $(this);
        if(button.hasClass('unBuyable')) return false;
        openCheckoutModal();
    });

    function validateFullName(name) {

        let words = name.split(" ");
        return words.length >= 2;
    }

    function validateAddress(address) {

        return address.trim() !== "";
    }

    checkoutForm.submit(function(event) {
        
        let fullName = $("#checkoutFullName").val();
        let address = $("#checkoutAddress").val();
        if (!validateFullName(fullName)) {
            checkoutAlert.text('Please enter a name and a surname!');
            checkoutAlert.removeClass('invisible');
            event.preventDefault();;
        }
        
        if (!validateAddress(address)){
            checkoutAlert.text('Please enter an address!');
            checkoutAlert.removeClass('invisible');
            event.preventDefault();;
        }

        
    });
    $(document).on('click', '#newProductBtn',function(){
        overlay.show()
        openNewProductModal();
    })
    $(document).on('click', '.closeNewProductModal', function(){
        overlayClickHandler();
    })
    function openNewProductModal(){

        overlay.show();
        body.addClass('overlayActive');
        $("#newProductModal").fadeIn('fast');
        overlay.on('click', overlayClickHandler)

    }


    $(document).on('click', '#addNewProductBtn', function(e){
        e.preventDefault();
        let alertText = '';
        let title = $("#newProductTitle").val();
        let thumbnail = $("#newProductThumbnail").prop('files')[0];
        let regular_price = parseInt($("#newProductRegularPrice").val());
        let sale_price = parseInt($("#newProductSalePrice").val());
        
        let formIsValid = true;
        if (title === ''){
            $("#newProductTitle").addClass('border border-danger');
            alertText = 'Product title is required!';
            formIsValid = false;
        };
        if (thumbnail === null){
            alertText += '<br>Thumbnail is required!';
            formIsValid = false;
        };
        if(regular_price === ''){
            alertText += '<br>Regular price is required!';
            formIsValid = false;
        }
        if(isNaN(regular_price)){
            alertText += '<br>Regular price must be an integer!';
            formIsValid = false;
        }
        if(sale_price === ''){
            alertText += '<br>Sale Price is required!';
            formIsValid = false;
        }
        if(isNaN(sale_price)){
            alertText += '<br>Sale price must be a integer!';
            formIsValid = false;
        }
        if(regular_price <= sale_price){
            alertText += '<br>Regular price must be greater than sale price';
            formIsValid = false;
        }
        if(formIsValid === false){
            $("#createNewProductAlertText").html(alertText);
            return false;
        }

        let data = new FormData();
        data.append('title', title);
        data.append('thumbnail', thumbnail);
        data.append('regular_price', regular_price);
        data.append('sale_price', sale_price);
        $.ajax({
            url: "admin/api/create-new-product",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function(response){
                let product = response.product;
                let newEl = `
                <div class="product flex flex-col items-start justify-center text-start gap-4 p-2 hover:border-2 hover:border-brand-light-border" data-product-id="${product.id}">
                    <div class="relative">
                        <img src="${product.thumbnail_path}" alt="Thumbnail image for a product">
                    </div>
                    <div class="px-4 font-semibold w-full ">
                        <h4>${product.title}</h4>
                        <div class="w-full py-2">
                            <label for="regularPrice" class="text-sm">Regular Price</label>
                            <input type="text" name="regularPrice" value="${product.regular_price}" class="regularPriceInput w-full p-2 border border-brand-light-border mb-2"></input>
                            <label for="salePrice" class="text-sm">Sale Price</label>
                            <input type="text" name="salePrice" value="${product.sale_price}" class="salePriceInput w-full p-2 border border-brand-light-border"></input>
                        </div>
                        
                        <button class="updateProductBtn mt-2 border border-brand-light-blue w-full p-2 rounded hover:bg-brand-light-blue hover:text-brand-light-gray text-sm">Update</button>
                        <button class="deleteProductBtn mt-2 border border-black w-full p-2 rounded hover:bg-black hover:text-white text-sm">Delete</button>
                    </div>
                </div>
                `;
                $("#editProductsSection").prepend(newEl);
                
            }
        })
    });

    $(document).on('click', '.updateProductBtn', function(){
        let card = $(this).closest('.product');
        let input_regular = card.find('.regularPriceInput');
        let input_sale = card.find('.salePriceInput');

        let product_id = parseInt(card.data('product-id'));
        let regular_price = parseInt(input_regular.val());
        let sale_price = parseInt(input_sale.val());

        if(regular_price <= sale_price){
            alert('Regular price must be greater than sale price');
            return false;
        } else {
            $.ajax({
                url: "admin/api/update-product",
                type: "POST",
                data: {
                    'productId': product_id,
                    'regular_price': regular_price,
                    'sale_price': sale_price,
                },
                success: function(response){
                    let product = response.product;
                    input_regular.val(product.regular_price);
                    input_sale.val(product.sale_price);
                }
            })
        }
    })
    $(document).on('click', '.deleteProductBtn', function(){
        let card = $(this).closest('.product');
        let product_id = parseInt(card.data('product-id'));

        $.ajax({
            url: "admin/api/delete-product",
            type: "POST",
            data: {
                'productId': product_id,
            },
            success: function(response){
                let product = response.product;
                card.remove();
            }
        })
        
    })
    


})

    
    