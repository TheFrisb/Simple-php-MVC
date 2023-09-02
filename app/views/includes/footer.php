<?php if(!empty($paginator)){
            echo '<div class="container text-center my-8 px-6 overflow-hidden text-ellipsis">';
            for ($i = 1; $i <= $totalPages; $i++) {
                if($i === $currentPage){
                    echo "<a href='?page=$i' class='p-4 border border-brand-light-border bg-brand-light-blue text-white'>$i</a>";
                } else {
                    echo "<a href='?page=$i' class='p-4 border border-brand-light-border hover:bg-brand-light-blue hover:text-white'>$i</a>";
                }
            }
            echo '</div>';
        }
?>
       
    
    <footer class="bg-brand-dark-text py-4">
        <div class="container text-white/60 text-sm">
            <p>© <?php echo date("Y") ?> Electronic Store. Powered by Electronic Store</p>
        </div>

    </footer>
    <div class="cartItem w-full py-4 border-b border-brand-light-border" data-product-id="" id="cartItemTemplate" style="display:none">
            <div class="flex items-center gap-2">
                <div class="flex items-center justify-center w-3/12">
                    <img src="#" alt="" class="cartItemThumbnail">
                </div>
            
                <div class="flex flex-col w-9/12 ">
                    <p class="mb-2 text-ellipsis text-justify overflow-hidden whitespace-nowrap cartItemTitle"></p>
                    <p><span class="cartItemQuantity"></span> x $<span class="cartItemSalePrice"></span></p>
                    <div class="flex items-center justify-start w-6/12 border border-brand-light-border">
                        <button class="w-3/12 p-2 border-r border-brand-light-border cartItemQuantityBtn cartItemQuantityMinus">-</button>
                        <input type="number" name="cartItemQty" class="p-2 w-6/12 text-center cartItemQuantityInput" value="">
                        <button class="w-3/12 p-2 border-l border-brand-light-border cartItemQuantityBtn">+</button>
                    </div>
                    <div class="text-xs py-1">
                        <span class="removeCartItem cursor-pointer">Remove</span>
                    </div>
                </div>
            </div>

    </div>
    
    <!-- Jquery Google CDN !-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- Catch CDN fail !-->
    <script>window.jQuery || document.write('<script src="../assets/js/jquery-3.7.0.min.js"><\/script>')</script>

    <!-- Main JS file (jQuery) !-->
    <script src="/ecommerce/assets/js/main.js"></script> 


</body>
</html>