/**
 * Requires: public/js/vue.js
 */
Vue.component('cart-table', {
    props: {
        my_cart: { type: Array, default: function () { return []; } }
    },
    template: `
        <div class='card mt-2'>
            <div class="card-header text-center">
                <h5>My cart</h5>
            </div>
            <div class='card-body pt-4 table-responsive'>
                <table class='table'>
                    <thead class="thead-dark">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, id) in my_cart">
                            <td>{{ product.product }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.single_price }}</td>
                            <td>{{ product.total_price }}</td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control" placeholder="Quantity to remove" type="number" min="0" max="999" v-model.number="product.to_remove">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-danger" @click="remove_from_cart(id)">Remove items</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    `,
    methods: {
        remove_from_cart: function (id) {
            let selected = this.my_cart[id]
            if ( typeof(selected.to_remove) == 'undefined' ){
                alert("Please insert the number of products you'd like to remove (more than 0)")
                return;
            }
            
            if ( selected.to_remove === '' || selected.to_remove === null ){
                alert("Please insert the number of products you'd like to remove (more than 0)")
                return;
            }

            if ( selected.to_remove < 1 ) {
                alert ('you cannot remove less than one element')
                return;
            }

            this.$emit('remove_from_cart', {
                product_id: selected.product_id,
                quantity: selected.to_remove
            });
        }
    }
})