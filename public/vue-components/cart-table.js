/**
 * Requires: public/js/vue.js
 */
Vue.component('cart-table', {
    props: {
        my_cart: { type: Array, default: function () { return []; } },
        is_purchasing: { type: Boolean, default: false }
    },
    template: `
        <div class='card mt-2 mb-4'>
            <div class="card-header">
                <div class="row">
                    <div class="col-6 text-center">
                        <h5 v-if="!is_purchasing">My cart</h5>
                        <h5 v-if="is_purchasing">Your cart</h5>
                    </div>
                    <div v-if="!is_purchasing" class="col-6 text-center">
                        <button v-if="my_cart.length > 0" class="btn btn-info" @click="call_purchase()">Buy</button>
                    </div>
                    <div v-if="is_purchasing" class="col-6 text-center">
                        <button class="btn btn-danger" @click="call_cancel_purchase()">Cancel purchase</button>
                    </div>
                </div>
            </div>
            <div class='card-body pt-4 table-responsive'>
                <table class='table'>
                    <thead class="thead-dark">
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th v-if="!is_purchasing" class="text-center">
                                <button class="btn btn-outline-light" @click="call_clear_cart()">Clear cart</button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="my_cart.length > 0" v-for="(product, id) in my_cart">
                            <td>{{ product.product }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.single_price }}</td>
                            <td>{{ product.total_price }}</td>
                            <td v-if="!is_purchasing">
                                <div class="input-group">
                                    <input class="form-control" placeholder="Quantity to remove" type="number" min="0" max="999" v-model.number="product.to_remove">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-danger" @click="remove_from_cart(id)">Remove items</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="my_cart.length == 0">
                            <td colspan="5">There are no products in cart to show</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="is_purchasing" class="card-footer">
                <button class="btn btn-block btn-warning" @click="call_finish_purchase()">See your final cart</button>
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
        },
        call_purchase: function () {
            this.$emit('call_purchase');
        },
        call_finish_purchase: function () {
            this.$emit('call_finish_purchase', true)
        },
        call_cancel_purchase: function () {
            this.$emit('call_cancel_purchase')
        },
        call_clear_cart: function () {
            this.$emit('call_clear_cart')
        }
    }
})