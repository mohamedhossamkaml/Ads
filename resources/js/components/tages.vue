<template>
<div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrAuthor()">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tages Table </h3>

                    <div class="card-tools">
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
                            <tr v-for="tages in tagess" :key="tages.id">
                                <td>{{ tages.id }}</td>
                                <td>{{ tages.title | upText }}</td>
                                <td>{{ tages.description }}</td>
                                <td v-if="$gate.isAdmin()">
                                    <a href="#" class=" col-5 " @click="editModal(tages)">
                                        <i class="fa fa-edit Teal"></i>
                                    </a>
                                    <a href="#" @click="deleteTages(tages.id)" class=" col-5 ">
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
                <form @submit.prevent="editmode ? updateTages() : createTages()" class="modal-body">
                    <div>
                        <div class="form-group">
                            <input v-model="form.title" type="text" id="title" name="title" placeholder="title" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                            <has-error :form="form" field="title"></has-error>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.description" id="description" name="description" placeholder="Short description for tages (Optional)" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
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
    name: "tages",
    data() {
        return {
            editmode: null,
            tagess: {},
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
        editModal(tages) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(tages);
        },

        createTages() { // Created tages Function //
            this.$Progress.start();
            this.form.post('api/tages')
                .then(req => {

                    Fire.$emit('AfterCreate');
                    $('#addNew').modal('hide');

                    if (req.data.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Created tages successfully'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'eroro tages successfully'
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

        updateTages(id) { // Updated tages Function //
            // console.log("Edit Function");
            this.$Progress.start();
            this.form.put('api/tages/' + this.form.id)
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
                    this.form.delete('api/tages/' + id).then(() => {
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

        loadTages() { // Reload Tages When Make Refresh The Page And When Added New Category //
            if(this.$gate.isAdminOrAuthor() ){
                this.$Progress.start();
                axios.get("api/tages")
                    .then(
                        ({
                            data
                        }) => (this.tagess = data.data),
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
        this.loadTages();
        Fire.$on('AfterCreate', () => {
            this.loadTages();
        });
        // setInterval(()=>this.loadTages(), 3000);
    },
    mounted() {},
}
</script>

<style>

</style>
