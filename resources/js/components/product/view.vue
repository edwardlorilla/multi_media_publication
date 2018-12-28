<template>
    <div>
        <grid-view :columns="columns"
                   :data="data"
                   :subscribe="true"
                   :handleSubscribe="handleSubscribe"
                   create-name="Add Product"
                   on-delete="/api/products"
                   on-edit-name="edit-product"
                   on-create-name="create-product"
                   @delete="data.data.splice($event, 1)"
        ></grid-view>
    </div>
</template>
<style>
</style>
<script>
    import GridView from './../Table/Grid.vue'
    export default{
        data(){
            return {
                columns: [
                    {
                        label: 'Name',
                        prop: 'name',
                        sort: true
                    },
                    {
                        label: 'Details',
                        prop: 'details',
                        sort: true
                    },

                ],
                data: []
            }
        },
        components: {
            GridView,
        },
        beforeRouteEnter (to, from, next) {
            axios.get(`/api/products`, {params: to.query}).then(function (response) {
                next(vm => vm.setData(response.data))
            })
        },
        beforeRouteUpdate (to, from, next) {
            var vm = this
            axios.get(`/api/products`, {params: to.query}).then(function (response) {
                vm.setData(response.data)
                next()
            })
        },
        methods: {
            handleSubscribe(index, row){
                var vm = this
                axios[row.isSubscribe ? 'delete' : 'get'](`/api/products/${row.id}/subscriptions`).then(function (response) {
                    vm.data.data[index].isSubscribe = response.data.isSubscribe
                }).catch(function (error) {

                })
            },
            setData(response){
                this.data = response
            },
        }
    }
</script>