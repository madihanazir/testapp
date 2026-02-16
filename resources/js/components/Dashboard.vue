<template>
  <div style="max-width:900px;margin:auto">
    <h2>User Management</h2>

    <table border="1" cellpadding="10" width="100%">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th v-if="isAdmin">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>
            <input v-model="user.name" :disabled="!isAdmin">
          </td>

          <td>
            <input v-model="user.email" :disabled="!isAdmin">
          </td>

          <td v-if="isAdmin" align="center">
            <button @click="updateUser(user)">Save</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="isAdmin" style="text-align:right;margin-top:15px">
      <button @click="showForm = !showForm">+ Add New User</button>
    </div>

    <div v-if="showForm" style="margin-top:15px">
      <input v-model="form.name" placeholder="Name">
      <input v-model="form.email" placeholder="Email">
      <input v-model="form.password" placeholder="Password" type="password">
      <button @click="addUser">Save</button>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: [],
      isAdmin: false,
      showForm: false,
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
      this.users = res.data.users;
      this.isAdmin = res.data.is_admin;
    },

    async addUser() {
      await axios.post('/api/users', this.form);
      this.form = { name:'', email:'', password:'' };
      this.showForm = false;
      this.loadUsers();
    },

    async updateUser(user) {
      await axios.put(`/api/users/${user.id}`, user);
      alert('Updated');
    }
  }
}
</script>
