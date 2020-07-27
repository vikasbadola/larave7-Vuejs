<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Tasks List
                    <span class="float-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow"><i class="fa fa-plus"> Add New</i></button>
                    </span>
                </h3>
                </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-striped table-hover">
                  <tbody>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th></th>
                  </tr> 

                  <tr v-for="task in tasks.data" :key="task.id" v-if="tasks.data.length">
                    <td>{{ task.name }}</td>
                    <td>{{ task.desc }}</td>
                    <td v-if="task.status == 'P'">{{ 'Pending' }}</td>
                    <td v-else>{{ 'Completed' }}</td>
                    <td>{{ task.created_a | formatDate}}</td>

                    <td>
                        <button class="btn btn-primary" href="#" data-id="task.id" @click="editModalWindow(task)">
                            <i class="fa fa-edit blue"></i> Edit 
                        </button>
                        |
                        <button class="btn btn-danger" href="#" @click="deleteTask(task.id)">
                            <i class="fa fa-trash red"></i> Delete
                        </button>

                    </td>
                  </tr>
                    <tr v-else>
                        <td colspan="5">"No records found"</td>
                    </tr>
                </tbody></table>
                <pagination :data="tasks" @pagination-change-page="loadTeams"></pagination>
              </div>
            
              <div class="card-footer">
                 
              </div>
            </div>
           
          </div>
        </div>


            <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">

                    <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Task</h5>
                    <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Task</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

<form @submit.prevent="editMode ? updateTask() : createTask()">
<div class="modal-body">
     <div class="form-group">
        <input v-model="form.name" type="text" name="name"
            placeholder="Name"
            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
        <has-error :form="form" field="name"></has-error>
    </div>
    <div class="form-group">
        <textarea v-model="form.desc" name="desc"
            placeholder="Description"
            class="form-control" :class="{ 'is-invalid': form.errors.has('desc') }">
        </textarea>
        <has-error :form="form" field="desc"></has-error>
    </div>
    <div class="form-group">
        <select name="status" v-model="form.status" id="status" class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
            <option value="">Select Status</option>
            <option value="P">Pending</option>
            <option value="C">Completed</option>
        </select>
        <has-error :form="form" field="status"></has-error>
    </div>
</div>
<div class="modal-footer">
    <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>

</form>

                </div>
            </div>
            </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                editMode: false,
                tasks: {},
                form: new Form({
                    id: '',
                    name : '',
                    desc: '',
                    status: ''
                })
            }
        },
        methods: {
        
        editModalWindow(task){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(task)
        },
        updateTask(){
           this.form.put('/api/task/'+this.form.id)
               .then(()=>{

                   Toast.fire({
                      icon: 'success',
                      title: 'Task updated successfully'
                    })

                    Fire.$emit('AfterCreatedTaskLoadIt');

                    $('#addNew').modal('hide');
               })
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.form.reset();
           $('#addNew').modal('show');
        },

        loadTeams(page) {
        if (typeof page === 'undefined') {
            page = 1;
        }
        axios.get("/api/task?page="+page).then( data => (this.tasks = data.data));

          //pick data from controller and push it into tasks object

        },

        createTask(){

            this.$Progress.start()

            this.form.post('/api/task')
                .then(() => {
                   
                    Fire.$emit('AfterCreatedTaskLoadIt'); //custom events

                        Toast.fire({
                          icon: 'success',
                          title: 'Task created successfully'
                        })

                        this.$Progress.finish()

                        $('#addNew').modal('hide');

                })
                .catch(() => {
                   console.log("Error......")
                })

     

            //this.loadTeams();
          },
          deleteTask(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                
              if (result.value) {
                //Send Request to server
                this.form.delete('/api/task/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Deleted!',
                              'Task deleted successfully',
                              'success'
                            )
                    this.loadTeams();

                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!',
                          footer: ''
                        })
                    })
                }

            })
          }
        },

        created() { 
            this.loadTeams();

            Fire.$on('AfterCreatedTaskLoadIt',()=>{ //custom events fire on
                this.loadTeams();
            });

        }
    }
</script> 