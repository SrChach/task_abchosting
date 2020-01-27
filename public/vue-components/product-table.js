/**
 * Requires: public/js/vue.js
 */
Vue.component('product-table', {
    props: {
        products: { type: Array, default: function () { return []; } }
    },
    template: `
        <div class='card mt-2'>
            <div class="card-header text-center">
                <h5>Products</h5>
            </div>
            <div class='card-body pt-4 table-responsive'>
                <table class='table'>
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Rate</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, id) in products">
                            <th>{{ product.id }}</th>
                            <td>{{ product.name }}</td>
                            <td>{{ product.price }}</td>
                            <td>{{ (product.rate == null) ? 'N/A' : product.rate}}</td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control" placeholder="quantity" type="number" min="0" max="999" v-model.number="product.to_cart">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning" @click="add_to_cart(id)">Add to cart</button>
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
        add_to_cart: function (id) {
            let selected = this.products[id]
            if ( selected.to_cart < 1 ) {
                alert ('you cannot add less than one element')
                return;
            }
            this.$emit('add_to_cart', {
				product_id: selected.id,
				quantity: selected.to_cart
            });
            this.products[id].to_cart = 0
        }
    }
})