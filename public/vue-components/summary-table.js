/**
 * Requires: public/js/vue.js
 */
Vue.component('summary-table', {
    props: {
        is_prefinal_buy: { type: Boolean, default: true },
        buyed_items: { type: Array, default: function () { return []; } },
        cart_price: { default: 0.00 },
        transport_price: { default: 0.00 },
        total_price: { default: 0.00 },
        your_cash: { default: 0.00 }
    },
    template: `
        <div class='card mt-2 mb-4'>
            <div class="card-header text-center">
                <h5 v-if="is_prefinal_buy">Are you sure?</h5>
                <h5 v-if="!is_prefinal_buy">Summary of purchase</h5>
            </div>
            <div class='card-body pt-4 table-responsive'>
                <table class='table'>
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Single price</th>
                            <th>Total price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, id) in buyed_items">
                            <th>{{ id }}</th>
                            <th>{{ product.product }}</th>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.single_price }}</td>
                            <td>{{ product.total_price }}</td>
                        </tr>
                    </tbody>
                    <tfoot class="thead-dark">
                        <tr>
                            <th>Cart price: {{ cart_price }}</th>
                            <th>Transport price: {{ transport_price }}</th>
                            <th>Total: {{ total_price }}</th>
                            <th colspan="2">Your cash now: {{ your_cash }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer text-center">
                <button v-if="is_prefinal_buy" class="btn btn-block btn-warning" @click="call_finish_purchase">
                    Finish buying
                </button>
                <button v-if="!is_prefinal_buy" class="btn btn-block btn-info" @click="show_main">
                    Done! Return to buy
                </button>
            </div>
        </div>
    `,
    methods: {
        show_main: function () {
            this.$emit('show_main', true);
        },
        call_finish_purchase: function () {
            this.$emit('call_finish_purchase', false);
        },
        
    }
})