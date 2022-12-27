<template>
<div class="container">
    <div class="row mt-5" >
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ads Table </h3>

                    <div class="card-tools">
                        <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div> -->
                        <button class="btn btn-success "      @click="newModal">
                            <i class="fa fa-user-plus fa-fw"></i>
                            Add New
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>title</th>
                                <th>description</th>
                                <th>Type</th>
                                <th>tags</th>
                                <th>advertiser</th>
                                <th>category</th>
                                <th>Start Dade</th>
                                <th>Modify</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ads in adss" :key="ads.id">
                                <td>{{ ads.id }}</td>
                                <td>{{ ads.title | upText }}</td>
                                <td>{{ ads.description }}</td>
                                <td><span class="tag tag-success">{{ ads.type | upText }}</span></td>
                                <td><span class="tag tag-success">{{ ads.advertiserid.name }}</span></td>
                                <td><span class="tag tag-success">{{ ads.category_id.title }}</span></td>
                                <td><span class="tag tag-success">{{ ads.tags_id.title  }}</span></td>
                                <td>{{ ads.created_at | myDate }}</td>
                                <td >
                                    <a href="#" class=" col-5 " @click="editModal(ads)">
                                        <i class="fa fa-edit Teal"></i>
                                    </a>
                                    <a href="#" @click="deleteAds(ads.id)" class=" col-5 ">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- <div class="row " v-else>
        <not-found/>
    </div> -->
    <!-- Button trigger modal
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Launch demo modal
        </button> -->
    <!-- Modal -->
    <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby='addNew' aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-show="+editmode" class="modal-title" id="addNew"> Update User </h5>
                    <h5 v-show="!editmode" class="modal-title" id="addNew"> Create User </h5>
                    <h5 v-show="editmode==null" class="modal-title" id="addNew"> Error Function </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updateAds() : createAds()" class="modal-body">
                    <div>
                        <div class="form-group">
                            <input v-model="form.title" type="text" id="title" name="title" placeholder="title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                            <has-error :form="form" field="title"></has-error>
                        </div>

                        <div class="form-group">
                        <label>Category</label>
                        <select v-model="form.category_id" name="category_id" id="category_id" class="form-control" :class="{ 'is-invalid': form.errors.has('category_id') }">
                            <option  v-for="cat in catss" :key="cat.id" :value="cat.id">{{ cat.title }}</option>
                        </select>
                        <has-error :form="form" field="category_id"></has-error>
                        </div>

                        <div class="form-group">
                        <label>Category</label>
                        <vue-multi-select
                            ref="multiSelect"
                            v-model="form.tags_id"
                            search
                            historyButton
                            :options="options"
                            :filters="filters"
                            :btnLabel="btnLabel"
                            @open="open"
                            @close="close"
                            :selectOptions="Tages">
                            <template v-slot:option="{option}">
                                <input type="checkbox" :checked="option.selected"/>
                                <span>{{option.title}}</span>
                            </template>
                        </vue-multi-select>
                        <has-error :form="form" field="tags_id"></has-error>
                        </div>






                        <div class="form-group">
                            <textarea v-model="form.description" id="description" name="description" placeholder="Short description for User (Optional)" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                            <has-error :form="form" field="description"></has-error>
                        </div>

                        <div class="form-group">
                            <label>Date:</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="date"  class="form-control pull-right" v-model="form.start_date" id="start_date" name="start_date" :class="{ 'is-invalid': form.errors.has('start_date') }">
                                <has-error :form="form" field="start_date"></has-error>
                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div>Type: {{ form.type }}</div>

                            <input type="radio" id="type" value="free" v-model="form.type"  :class="{ 'is-invalid': form.errors.has('type') }"/>
                            <label for="free">free</label>

                            <input type="radio" id="two" value="paid" v-model="form.type" :class="{ 'is-invalid': form.errors.has('type') }" />
                            <label for="paid">paid</label>
                            <has-error :form="form" field="type"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success"> Update </button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary"> create </button>
                        <button v-show="editmode==null" type="button" class="btn btn-warning"> Error </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import vueMultiSelect from 'vue-multi-select';
import 'vue-multi-select/dist/lib/vue-multi-select.css';
import Multiselect from 'vue-multiselect'

export default {
    name: "ads",
    components:{
        "vue-select": require("vue-select")
    },
    data() {
        return {
            btnLabel: Tages => `A simple vue multi select (${Tages.id})`,
            editmode: null,
            adss: {} ,
            catss:{},
            Tages:{
                selectOptions: {
                type: Array,
                default: () => [],
                },
            },
            form: new Form({
                id: '',
                title:'',
                description: '',
                type: '' ,
                advertiserid: '' ,
                category_id: '' ,
                tags_id: [] ,
                start_date: '' ,
            }),
            filters: [{
                nameAll: 'Select all',
                nameNotAll: 'Deselect all',
                func() {
                return true;
                },
            }, {
                nameAll: 'select <= 10',
                nameNotAll: 'Deselect <= 10',
                func(elem) {
                return elem.name <= 10;
                },
            },],
            filters: [{
                nameAll: 'Select all',
                nameNotAll: 'Deselect all',
                func() {
                return true;
                },
            }],
            options: {
                multi: true,
                groups: true,
                labelName: 'label',
                labelList: 'elements',
                groupName: 'title',
                cssSelected: option => (option.selected ? { 'background-color': '#5764c6' } : ''),
            },
            selectOptions: {
            type: Array,
            default: () => [],
            },
        }
    },
    methods: {
        reloadFunction() {
            this.Tages = [];
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
        },
        editModal(Ads) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(Ads);
        },

            open() {
            console.log('open');
            },
            close() {
            console.log('close');
            },




        createAds() { // Created Ads Function //
            this.$Progress.start();
            this.form.post('api/ads')
                .then(req => {

                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide');

                    if (req.data.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Created Ads successfully'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'eroro Ads successfully'
                        });
                    }
                })
                .catch(() => {
                    $('#addNew').modal('hide');
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    })
                    this.$Progress.fail();
                });
            // this.$Progress.finish();
        },



        updateAds(id) { // Updated Ads Function //
            // console.log("Edit Function");
            this.$Progress.start();
            this.form.put('api/ads/' + this.form.id)
                .then(() => {
                    // Success
                    $('#addNew').modal('hide');
                    Fire.$emit('AfterCreate');
                    Swal.fire(
                        'Updated!',
                        'Information Has Been Updated. :)',
                        'success'
                    )
                    this.$Progress.finish();
                })
                .catch(() => {
                    $('#addNew').modal('hide');
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    })
                    this.$Progress.fail();
                });
            // this.$Progress.finish();
        },

        deleteAds(id) { // Delete Ads Function //
            // Sweet Alert2 Model //
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                this.$Progress.start();
                if (result.value) {
                    // Send Reuest To the Server //
                    this.form.delete('api/ads/' + id).then(() => {
                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                            this.$Progress.finish();
                        })
                        .catch(() => {
                            Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            })
                            this.$Progress.fail();
                        });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your imaginary file is safe :)',
                        'error'
                    )
                    this.$Progress.fail();
                }
            }); // End Sweet Alert2 Model //
        },
        loadAds() { // Reload ads When Make Refresh The Page And When Added New ads //
            if(this.$gate.isAdminOrAuthor() ){
                this.$Progress.start();
                axios.get("api/ads")
                    .then(
                        ({
                            data
                        }) => (this.adss = data.data),
                        this.$Progress.finish()
                    )
                    .catch(() => {
                        this.$Progress.fail();
                    });
                // this.$Progress.finish();
            }else{
                this.$Progress.start();
                axios.get("api/adsuser")
                    .then(
                        ({
                            data
                        }) => (this.adss = data.data),
                        this.$Progress.finish()
                    )
                    .catch(() => {
                        this.$Progress.fail();
                    });
            }
        },
        loadCatgory() { // Reload ads When Make Refresh The Page And When Added New ads //

                this.$Progress.start();
                axios.get("api/adscat")
                    .then(
                        ({
                            data
                        }) => (this.catss = data.data),
                        this.$Progress.finish()
                    )
                    .catch(() => {
                        this.$Progress.fail();
                    });
                // this.$Progress.finish();

        },
        loadTages() { // Reload ads When Make Refresh The Page And When Added New ads //
                this.$Progress.start();
                axios.get("api/adstages")
                    .then(
                        ({
                            data
                        }) => (this.Tages.value = data.data),
                        console.log(this.Tages),
                        this.$Progress.finish()
                    )
                    .catch(() => {
                        this.$Progress.fail();
                    });
                // this.$Progress.finish();

        },

    },
    components: {
            vueMultiSelect,
        },
    created() {
        this.loadAds();
        this.loadCatgory();
        this. loadTages();
        Fire.$on('AfterCreate', () => {
            this.loadAds();
            this.loadCatgory();
            this. loadTages();
        });
        // setInterval(()=>this.loadAds(), 3000);
    },
    mounted() {},
}
</script>
<style src="vue-advanced-search/dist/AdvancedSearch.css"></style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>

</style>
