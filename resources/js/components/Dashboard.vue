<template>
  <div style="max-width:900px;margin:auto">
    <h2>User Management</h2>

    <table border="1" cellpadding="10" width="100%">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          
        </tr>
      </thead>

      <tbody>

        <!-- Existing users -->
        <tr v-for="user in users" :key="'u'+user.id">
          <td>
                      <input v-model="user.name" :disabled="!isAdmin"
            @blur="updateUser(user)"
          >

          </td>
          <td>
                  <input v-model="user.email" :disabled="!isAdmin"
            @blur="updateUser(user)"
          >

          </td>
          
        </tr>

        <!-- Newly added users (not yet saved) -->
        <tr v-for="(user,index) in newUsers" :key="'n'+index">
          <td>
            <input v-model="user.name" placeholder="Name">
          </td>
          <td>
            <input v-model="user.email" placeholder="Email">
          </td>
          <td>
            <button @click="removeNew(index)">X</button>
          </td>
        </tr>

      </tbody>
    </table>

    <!-- Controls -->
    <div v-if="isAdmin" style="margin-top:15px;display:flex;gap:10px;justify-content:flex-end">
      <button @click="addRow">+ Add New User</button>
      <button @click="saveAll" :disabled="!newUsers.length">Save All</button>
    </div>

  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      users: [],
      newUsers: [],
      isAdmin: false,
      savedRows: {} // Track which rows have been recently saved
    }
  },

  mounted() {
    this.loadUsers()
  },

  methods: {

    async loadUsers() {
      const res = await axios.get('/api/users')
      this.users = res.data.users
      this.isAdmin = res.data.is_admin
    },

    addRow() {
      this.newUsers.push({
        name: '',
        email: '',
        password: '123456'
      })
    },

    removeNew(index) {
      this.newUsers.splice(index,1)
    },

    async saveAll() {
      if (!this.newUsers.length) return

      await axios.post('/api/users/bulk', {
        users: this.newUsers
      })

      this.newUsers = []
      this.loadUsers()
      alert('Users saved successfully')
    },

    async updateUser(user) {
    await axios.put(`/api/users/${user.id}`, user)

    this.savedRows[user.id] = true

    setTimeout(() => {
      this.savedRows[user.id] = false
    }, 1500)
}

  }
}
</script>
