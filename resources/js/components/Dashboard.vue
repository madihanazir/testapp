<template>
  <div style="max-width:900px;margin:auto">
    <h2>User Management</h2>

    <table border="1" cellpadding="10" width="100%">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th width="140">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(user, index) in users" :key="user.id">
          <td>
            <input v-model="user.name" />
          </td>

          <td>
            <input v-model="user.email" />
          </td>

          <td align="center">
            <button @click="updateUser(user)">Save</button>
          </td>
        </tr>

        <!-- ADD NEW USER ROW -->
        <tr>
          <td>
            <input v-model="form.name" placeholder="New name">
          </td>
          <td>
            <input v-model="form.email" placeholder="New email">
          </td>
          <td align="center">
            <input v-model="form.password" placeholder="Password" type="password" style="width:95px">
            <br><br>
            <button @click="addUser">+ Add</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: [],
      form: {
        name: '',
        email: '',
        password: ''
      }
    }
  },

  mounted() {
    this.loadUsers();
  },

  methods: {
    async loadUsers() {
      const res = await axios.get('/api/users');
      this.users = res.data;
    },

    async addUser() {
      await axios.post('/api/users', this.form);
      this.form = { name:'', email:'', password:'' };
      this.loadUsers();
    },

    async updateUser(user) {
      await axios.put(`/api/users/${user.id}`, user);
      alert('Updated');
    }
  }
}
</script>
