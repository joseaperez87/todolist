import axiosClient from "../axios";

class TodoListServices {
  /**
   * Check registration email
   */
  static async getUserList(){
    return axiosClient.get('/user/list')
      .then(({data}) => {
        return data;
      })
  }

  static async create(todo){
    return axiosClient.post('/todo/create', todo)
      .then(({data}) => {
        return data;
      })
  }
  static async get(id){
    return axiosClient.post('/todo/get', {id})
      .then(({data}) => {
        return data;
      })
  }
  static async remove(id){
    return axiosClient.post('/todo/remove', {id})
      .then(({data}) => {
        return data;
      })
  }
}

export default TodoListServices
