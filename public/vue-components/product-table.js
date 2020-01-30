/**
 * Requires: public/js/vue.js
 */
Vue.component('product-table', {
    props: {
        products: { type: Array, default: function () { return []; } },
        is_rating: { type: Boolean, default: false }
    },
    template: `
        <div class='card mt-2 mb-4'>
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
                            <th v-if="!is_rating">Rate</th>
                            <th>Image</th>
                            <th v-if="!is_rating">
                                <button @click="show_rate(true)" class="btn btn-outline-light btn-block">
                                    Rate products
                                </button>
                            </th>
                            <th v-if="is_rating">
                                <button @click="show_rate(false)" class="btn btn-outline-light btn-block">
                                    Show product list
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="products.length > 0" v-for="(product, id) in products">
                            <th>{{ product.id }}</th>
                            <td>{{ product.name }}</td>
                            <td>{{ product.price }}</td>
                            <td v-if="!is_rating">{{ (product.rate == null) ? 'N/A' : product.rate + '/5'}}</td>
                            <td>
                                <img v-if="typeof(product.image) == 'string'" :src="'public/images/' + product.image" alt="Product" height="42" width="42">
                                <span v-if="typeof(product.image) != 'string'">N/A</span>
                            </td>
                            <td v-if="!is_rating">
                                <div class="input-group">
                                    <input class="form-control" placeholder="quantity" type="number" min="0" max="999" v-model.number="product.to_cart">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning" @click="add_to_cart(id)">Add to cart</button>
                                    </div>
                                </div>
                            </td>
                            <td v-if="is_rating">
                                <div class="input-group">
                                    <input class="form-control" placeholder="Your rate" type="number" min="0" max="999">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-warning">Rate this product!</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="products.length == 0">
                            <td colspan="5">There are no products to show</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="is_rating" class="card-header text-center">
                <small>Note: You can only rate products that you've purchased</small>
            </div>
        </div>
    `,
    methods: {
        add_to_cart: function (id) {
            let selected = this.products[id]
            if (selected.to_cart < 1) {
                alert('you cannot add less than one element')
                return;
            }
            this.$emit('add_to_cart', {
                product_id: selected.id,
                quantity: selected.to_cart
            });
            this.products[id].to_cart = 0
        },
        show_rate: function (is_rating) {
            this.$emit('show_rate', is_rating)
        }
    }
})