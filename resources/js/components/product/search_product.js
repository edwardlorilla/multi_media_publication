/**
 * Created by Edward Lance Lorilla on 12/27/2018.
 */
export default {
    data(){
        return {
            products:[]
        }
    },
    methods:{
        search_product(query){
            var vm = this
            if (query !== '') {
                vm.onSearchProduct(query, vm)
            } else {
                vm.loading = false
                vm.products = []
            }
        },
        onSearchProduct: _.debounce(function (query, vm) {
            vm.loading = true
            axios.get('/api/products/search?search=' + query).then(function (q) {
                vm.loading = false
                vm.products = q.data.map(item => {
                    return {value: item.id, label: item.details};
                })
            }).catch(function () {
                vm.loading = false
            })
        }, 350),
    }
}