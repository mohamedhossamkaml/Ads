<template>
<div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Categorys Table </h3>

                    <div class="card-tools">
                        <!-- <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div> -->
                        <button class="btn btn-success " v-if="$gate.isAdmin()"  @click="newModal">
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
                                <th v-if="$gate.isAdmin()">Modify</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="category in categorys" :key="category.id">
                                <td>{{ category.id }}</td>
                                <td>{{ category.title | upText }}</td>
                                <td>{{ category.description }}</td>
                                <td v-if="$gate.isAdmin()">
                                    <a href="#" class=" col-5 " @click="editModal(category)">
                                        <i class="fa fa-edit Teal"></i>
                                    </a>
                                    <a href="#" @click="deleteCategory(category.id)" class=" col-5 ">
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
    <div class="row " v-else>
        <not-found/>
    </div>
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
                <form @submit.prevent="editmode ? updateCategory() : createCategory()" class="modal-body">
                    <div>
                        <div class="form-group">
                            <input v-model="form.title" type="text" id="title" name="title" placeholder="title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                            <has-error :form="form" field="title"></has-error>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.description" id="description" name="description" placeholder="Short description for User (Optional)" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                            <has-error :form="form" field="description"></has-error>
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
export default {
    name: "category",
    data() {
        return {
            editmode: null,
            categorys: {},
            form: new Form({
                id: '',
                title:'',
                description: '',
            }),
        }
    },
    methods: {
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
        },
        editModal(category) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(category);
        },

        createCategory() { // Created category Function //
            this.$Progress.start();
            this.form.post('api/category')
                .then(req => {

                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide');

                    if (req.data.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Created Category successfully'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'eroro Category successfully'
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

        updateCategory(id) { // Updated Category Function //
            // console.log("Edit Function");
            this.$Progress.start();
            this.form.put('api/category/' + this.form.id)
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

        deleteCategory(id) { // Delete Category Function //
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
                    this.form.delete('api/category/' + id).then(() => {
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

        loadCategory() { // Reload Category When Make Refresh The Page And When Added New Category //
            if(this.$gate.isAdminOrAuthor() ){
                this.$Progress.start();
                axios.get("api/category")
                    .then(
                        ({
                            data
                        }) => (this.categorys = data.data),
                        this.$Progress.finish()
                    )
                    .catch(() => {
                        this.$Progress.fail();
                    });
                // this.$Progress.finish();
            }
        },
    },
    created() {
        this.loadCategory();
        Fire.$on('AfterCreate', () => {
            this.loadCategory();
        });
        // setInterval(()=>this.loadCategory(), 3000);
    },
    mounted() {},
}
</script>

<style>

</style>
