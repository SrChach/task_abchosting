/**
 * Requires: public/js/vue.js
 */
Vue.component('transport-table', {
    props: {
        transport_types: { type: Array, default: function () { return []; } }
    },
    template: `
        <div class='card mt-2 mb-4'>
            <div class="card-header text-center">
                <h5>Select transport type</h5>
            </div>
            <div class='card-body pt-4 table-responsive'>
                <table class='table'>
                    <thead class="thead-dark">
                        <tr>
                            <th>Transport type</th>
                            <th>Price</th>
                            <th>Selected</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="transport_types.length > 0" v-for="(transport_type, id) in transport_types">
                            <td>{{ transport_type.name }}</td>
                            <td>{{ transport_type.price }}</td>
                            <th>{{ (transport_type.id == selected_id) ? 'Yes' : 'No' }}</th>
                            <td>
                                <button v-if="transport_type.id != selected_id" class="btn btn-outline-warning" @click="set_transport_type(transport_type.id)">Select</button>
                            </td>
                        </tr>
                        <tr v-if="transport_types.length == 0">
                            <td colspan="4">There are no transport types to show</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    `,
    data: function () {
        return {
            selected_id: null
        }
    },
    methods: {
        set_transport_type: function (id) {
            this.selected_id = id
            this.$emit('set_transport_type', id);
        }
    }
})