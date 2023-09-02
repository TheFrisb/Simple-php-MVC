{% extends 'base.html' %}
{% block header %}

<body class="bg-brand-dark-gray font-poppins text-brand-dark-text">

    <div id="overlay" class="fixed w-full h-full top-0 left-0 right-0 bottom-0 z-10 bg-black/40" style="display:none"></div>
    <div id="cart" class="fixed w-[100%] md:w-[65%] lg:w-[320px] h-full top-0  right-0 bg-brand-light-gray z-20 overflow-y-auto">
        <div class="cart-container w-full h-full">
            <div class="flex items-center justify-between w-full border-b border-brand-light-border">
                <p class="p-4">Shopping cart</p>
                <div class="closeCart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="29" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </div>
            <div class="flex flex-col justify-between px-4 pb-8 py-4">
                
                <div id="cartContent" class="flex flex-col Buyable">
                    <?php if(empty($_SESSION['cart'])): ?>
                        <p id="emptyCartText" class="mb-4">No items in your cart yet!</p>
                    <?php else: ?>
                        <p id="emptyCartText" class="mb-4" style="display:none">No items in your cart yet!</p>
                        <?php foreach($_SESSION['cart'] as $productId => $cartItem): ?>
                            <div class="cartItem w-full py-4 border-b border-brand-light-border" data-product-id="<?php echo $productId ?>">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center justify-center w-3/12">
                                        <img src="<?php echo $cartItem['thumbnail'] ?>" alt="">
                                    </div>
                                
                                    <div class="flex flex-col w-9/12 ">
                                        <p class="mb-2 text-ellipsis text-justify overflow-hidden whitespace-nowrap"><?php echo $cartItem['title'] ?></p>
                                        <p><span class="cartItemQuantity"><?php echo $cartItem['currentQuantity'] ?></span></span> x $<?php echo $cartItem['sale_price'] ?></p>
                                        <div class="flex items-center justify-start w-6/12 border border-brand-light-border">
                                            <button class="w-3/12 p-2 border-r border-brand-light-border cartItemQuantityBtn cartItemQuantityMinus">-</button>
                                            <input type="number" name="cartItemQty" class="p-2 w-6/12 text-center cartItemQuantityInput" value="<?php echo $cartItem['currentQuantity'] ?>">
                                            <button class="w-3/12 p-2 border-l border-brand-light-border cartItemQuantityBtn">+</button>
                                        </div>
                                        <div class="text-xs py-1">
                                            <span class="removeCartItem cursor-pointer">Remove</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                        

                    </div>
                    <?php if(empty($_SESSION['cart'])): ?>
                        <div class="flex items-center justify-between w-full border-b border-brand-light-border py-4 mb-4" id="cartTotalSection" style="display:none">
                            <p class="">Subtotal: $<span id="cartTotal"><?php echo $_SESSION['cartTotal'] ?></span></p>
                        </div>

                        <div class="">
                            <button class="p-2 bg-brand-light-blue text-white w-full hover:bg-brand-dark-blue closeCart unBuyable" id="checkoutBtn">Continue shopping</button>
                        </div>

                    <?php else: ?>
                        <div class="flex items-center justify-between w-full border-b border-brand-light-border py-4 mb-4" id="cartTotalSection">
                            <p class="">Subtotal: $<span id="cartTotal"><?php echo $_SESSION['cartTotal'] ?></span></p>
                        </div>

                        <div class="">
                            <button class="p-2 bg-brand-light-blue text-white w-full hover:bg-brand-dark-blue" id="checkoutBtn">Checkout</button>
                        </div>
                    <?php endif ?>
                    


            </div>
        </div>
    </div>
    <div id="checkoutModal" class="fixed w-5/6 h-auto md:w-8/12 md:h-auto lg:w-6/12 lg:h-auto xl:w-4/12 xl:h-4/12 bg-brand-light-gray z-50 overflow-y-auto border border-brand-light-border shadow-xl" style="display:none">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-semibold ">Checkout</h4>
                <div class="closeCheckout cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </div>
            </div>
            <div class="border border-brand-light-blue/60 p-4">

                <form action="form/place-order" method="post" class="flex flex-col items-start justify-center w-full" id="checkoutForm">
                    <div class="mb-2 w-full">
                        <label for="checkoutFullName">Full Name</label>
                        <input type="text" name="checkoutFullName" id="checkoutFullName" placeholder="Aleksandar Bedjovski" class="w-full p-2 border border-brand-light-border" required>
                    </div>
                    <div class="mb-2 w-full">
                        <label for="checkoutCountry">Country</label>
                        <input type="text" name="checkoutCountry" placeholder="Macedonia" readonly class="w-full p-2" required>
                    </div>
                    <div class="mb-2 w-full">
                        <label for="checkoutAddress">Address</label>
                        <input type="text" name="checkoutAddress" id="checkoutAddress" placeholder="Street name" class="w-full p-2 border border-brand-light-border" required>
                    </div>
                    <table class="table-auto w-full text-start mb-2">
                        <thead class="text-start">
                            <tr>
                            <th class="text-start">Product</th>
                            <th class="text-start">Price</th>
                            </tr>
                        </thead>
                        <tbody id="checkoutItemsContent">
                            <?php if(!empty($_SESSION['cart'])): ?>
                                <?php foreach($_SESSION['cart'] as $productId => $checkoutItem): ?>
                                    <tr class="checkoutItem" data-product-id="<?php echo $productId ?>">
                                    <td>
                                        <span class="checkoutItemQuantity"><?php echo $checkoutItem['currentQuantity'] ?></span> x <span class="font-semibold checkoutItemTitle"><?php echo $checkoutItem['title'] ?></span>
                                    </td>
                                    <td>
                                        <span class="checkoutItemSalePrice"><?php echo $checkoutItem['sale_price'] ?></span>$
                                    </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
                        </tbody>
                        <tfoot class="border-t border-brand-light-border">
                            <tr>
                                <td>Total:</td>
                                <td><span id="checkoutTotal"><?php echo $_SESSION['cartTotal'] ?></span>$</td>
                            </tr>
                        </tfoot>
                    </table>
                    <p class="mb-2 text-sm text-red-700 invisible" id="checkoutAlertText">Please enter a name</p>
                    <button class="w-full btn bg-brand-light-blue p-2 mb-2 text-white" id="place_order">Place order</button>
                </form>
            </div>
        </div>
    </div>
    <div class="banner w-full bg-brand-dark-blue text-white">
        <p class="container p-2 text-sm">24/7 Customer service <a href="https://github.com/TheFrisb" class="font-bold">Github/TheFrisb</a></p>
    </div>
    <header class="w-full bg-brand-light-blue text-white" >
        
        <div class="container flex align-center justify-between p-4 ">
            <a class="flex items-center justify-start" href="shop">
                <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" width="123" height="24" viewBox="0 0 123 24" fill="none">
                    <circle cx="12" cy="12" r="8" stroke="#E3FF39" stroke-width="8"></circle>
                    <path d="M37.968 11.312H35.808V20H32.496V11.312H30.84V8H32.496V4.232H35.808V8H37.968V11.312ZM46.6113 11.384C46.3953 11.224 46.1553 11.1 45.8913 11.012C45.6273 10.916 45.3353 10.868 45.0153 10.868C44.4713 10.868 44.0073 11.008 43.6233 11.288C43.2473 11.56 42.9593 11.928 42.7593 12.392C42.5673 12.856 42.4713 13.372 42.4713 13.94V20H39.1353V8H42.4473V9.236C42.8313 8.772 43.2833 8.4 43.8033 8.12C44.3313 7.832 44.9313 7.688 45.6033 7.688C45.8753 7.688 46.1393 7.696 46.3953 7.712C46.6513 7.72 46.8993 7.76 47.1393 7.832L46.6113 11.384ZM52.4622 20.312C51.3582 20.312 50.3502 20.028 49.4382 19.46C48.5342 18.892 47.8102 18.132 47.2662 17.18C46.7302 16.22 46.4622 15.16 46.4622 14C46.4622 13.12 46.6182 12.3 46.9302 11.54C47.2422 10.772 47.6702 10.1 48.2142 9.524C48.7662 8.94 49.4062 8.484 50.1342 8.156C50.8622 7.828 51.6382 7.664 52.4622 7.664C53.5662 7.664 54.5702 7.948 55.4742 8.516C56.3862 9.084 57.1102 9.848 57.6462 10.808C58.1902 11.768 58.4622 12.832 58.4622 14C58.4622 14.872 58.3062 15.688 57.9942 16.448C57.6822 17.208 57.2502 17.88 56.6982 18.464C56.1542 19.04 55.5182 19.492 54.7902 19.82C54.0702 20.148 53.2942 20.312 52.4622 20.312ZM52.4622 17C52.9742 17 53.4342 16.864 53.8422 16.592C54.2502 16.312 54.5702 15.944 54.8022 15.488C55.0342 15.032 55.1502 14.536 55.1502 14C55.1502 13.448 55.0262 12.944 54.7782 12.488C54.5382 12.024 54.2142 11.656 53.8062 11.384C53.3982 11.112 52.9502 10.976 52.4622 10.976C51.9582 10.976 51.5022 11.116 51.0942 11.396C50.6862 11.676 50.3622 12.044 50.1222 12.5C49.8902 12.956 49.7742 13.456 49.7742 14C49.7742 14.568 49.8942 15.08 50.1342 15.536C50.3822 15.984 50.7102 16.34 51.1182 16.604C51.5262 16.868 51.9742 17 52.4622 17ZM71.0198 13.868V20H67.7078V13.916C67.7078 13.356 67.5918 12.844 67.3598 12.38C67.1278 11.916 66.7998 11.544 66.3758 11.264C65.9598 10.984 65.4758 10.844 64.9238 10.844C64.3798 10.844 63.9158 10.984 63.5318 11.264C63.1478 11.544 62.8558 11.916 62.6558 12.38C62.4558 12.844 62.3558 13.356 62.3558 13.916V20H59.0438V8H62.3558V9.236C62.7398 8.764 63.1918 8.384 63.7118 8.096C64.2398 7.808 64.8398 7.664 65.5118 7.664C66.6398 7.664 67.6118 7.944 68.4278 8.504C69.2518 9.064 69.8878 9.816 70.3358 10.76C70.7838 11.696 71.0118 12.732 71.0198 13.868ZM72.1949 20V8H75.5069V8.984C75.8509 8.672 76.2029 8.42 76.5629 8.228C76.9309 8.028 77.3269 7.928 77.7509 7.928C78.5109 7.928 79.2229 8.076 79.8869 8.372C80.5589 8.668 81.1469 9.088 81.6509 9.632C82.1629 9.08 82.7509 8.66 83.4149 8.372C84.0869 8.076 84.8029 7.928 85.5629 7.928C86.6029 7.928 87.5429 8.184 88.3829 8.696C89.2229 9.208 89.8869 9.892 90.3749 10.748C90.8709 11.604 91.1189 12.552 91.1189 13.592V20H87.8069V13.592C87.8069 13.152 87.7069 12.756 87.5069 12.404C87.3149 12.052 87.0509 11.772 86.7149 11.564C86.3789 11.348 85.9949 11.24 85.5629 11.24C85.1389 11.24 84.7549 11.348 84.4109 11.564C84.0749 11.772 83.8069 12.052 83.6069 12.404C83.4069 12.756 83.3069 13.152 83.3069 13.592V20H79.9949V13.592C79.9949 13.152 79.8949 12.756 79.6949 12.404C79.5029 12.052 79.2349 11.772 78.8909 11.564C78.5469 11.348 78.1669 11.24 77.7509 11.24C77.3349 11.24 76.9549 11.348 76.6109 11.564C76.2669 11.78 75.9949 12.068 75.7949 12.428C75.6029 12.78 75.5069 13.176 75.5069 13.616V20H72.1949ZM101.028 8H104.34V20H101.016L100.872 18.752C100.56 19.216 100.16 19.592 99.6724 19.88C99.1844 20.168 98.6124 20.312 97.9564 20.312C97.0604 20.312 96.2204 20.144 95.4364 19.808C94.6604 19.472 93.9764 19.008 93.3844 18.416C92.8004 17.824 92.3404 17.14 92.0044 16.364C91.6764 15.58 91.5124 14.74 91.5124 13.844C91.5124 12.988 91.6684 12.188 91.9804 11.444C92.2924 10.7 92.7324 10.044 93.3004 9.476C93.8684 8.908 94.5204 8.464 95.2564 8.144C96.0004 7.824 96.7964 7.664 97.6444 7.664C98.3804 7.664 99.0444 7.82 99.6364 8.132C100.228 8.444 100.744 8.84 101.184 9.32L101.028 8ZM98.0524 17.132C98.6204 17.132 99.1204 16.984 99.5524 16.688C99.9844 16.384 100.312 15.984 100.536 15.488C100.76 14.992 100.836 14.444 100.764 13.844C100.7 13.292 100.52 12.792 100.224 12.344C99.9284 11.888 99.5524 11.528 99.0964 11.264C98.6484 11 98.1644 10.868 97.6444 10.868C97.0764 10.868 96.5724 11.016 96.1324 11.312C95.6924 11.608 95.3564 12.004 95.1244 12.5C94.8924 12.996 94.8044 13.548 94.8604 14.156C94.9324 14.7 95.1204 15.2 95.4244 15.656C95.7364 16.104 96.1204 16.464 96.5764 16.736C97.0404 17 97.5324 17.132 98.0524 17.132ZM113.705 11.384C113.489 11.224 113.249 11.1 112.985 11.012C112.721 10.916 112.429 10.868 112.109 10.868C111.565 10.868 111.101 11.008 110.717 11.288C110.341 11.56 110.053 11.928 109.853 12.392C109.661 12.856 109.565 13.372 109.565 13.94V20H106.229V8H109.541V9.236C109.925 8.772 110.377 8.4 110.897 8.12C111.425 7.832 112.025 7.688 112.697 7.688C112.969 7.688 113.233 7.696 113.489 7.712C113.745 7.72 113.993 7.76 114.233 7.832L113.705 11.384ZM122.163 11.312H120.003V20H116.691V11.312H115.035V8H116.691V4.232H120.003V8H122.163V11.312Z" fill="white"></path>
                </svg>
            </a>
            <div class="md:flex items-center gap-4 font-semibold hidden">
                <a class="cursor-pointer" href="edit-products">Edit products</a>
                <a class="cursor-pointer" href="view-orders">View orders</a>
            </div>
            <div class="relative cursor-pointer p-2" id="cartIcon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <span class="absolute top-[-4px] right-[-4px] text-xs  text-brand-dark-blue px-1.5 py-0.5 rounded-2xl bg-brand-light-gray border border-brand-dark-blue" id="cartQuantity"><?php echo $_SESSION['cartTotalItems'] ?></span>
            </div>
        </div>
    </header>
    <div class="w-full bg-brand-light-blue text-white md:hidden">
        <div class="container p-4">
            <div class=" items-center gap-4 font-semibold flex">
                <a class="cursor-pointer" href="edit-products">Edit products</a>
                <a class="cursor-pointer" href="view-orders">View orders</a>
            </div>
        </div>
    </div>
{% endblock header %}
