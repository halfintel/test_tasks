<template>
  <div class="ProjectsTable">
    <table>
      <thead>
        <tr>
          <th class="th">Назва проекту</th>
          <th class="th">Бюджет</th>
          <th class="th">Ім'я та логін замовника</th>
        </tr>
      </thead>
      <tbody>
        <tr class="tr" v-for="(item, index) in items" :key="index">
          <td class="td"><a href="{{ item.link }}">{{ item.name }}</a></td>
          <td class="td">{{ item.budget }} {{ item.currency }}</td>
          <td class="td">{{ item.employer_name }} ({{ item.login }})</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
const baseUrl = 'http://localhost:8001/'; // TODO: перенести у конфіг
export default {
  name: 'ProjectsTable',
  props: {
  },
  data() {
    return { items: [] }
  },
  created() {
    fetch(baseUrl + "?path=projects")
      .then(response => response.json())
      .then(response => {this.items = response.message;});
  },
  // TODO: додати Pie chart та категорії
  // TODO: додати лоадер та помилку, якщо бек не працює
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.ProjectsTable {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: flex-start;
}
.th, .td {
  padding: 15px;
  text-align: left;
}
</style>
