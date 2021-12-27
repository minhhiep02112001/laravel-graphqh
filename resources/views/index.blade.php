<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<div class="container pt-3">
    <div class="row">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <div style="max-height: 600px; overflow: auto">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody id="content-user">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h2 id="title-action">Thêm User</h2>
                </div>
                <div class="card-body">
                    <form id="form-input-data">

                        <input type="hidden" style="display: none" name="id" class="form-control" id="id" disabled
                               aria-describedby="emailHelp">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" aria-describedby="emailHelp">

                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password_confirmation">
                        </div>
                        <button type="button" class="btn btn-primary" id="btn-submit-data">Submit</button>
                        <button type="button" class="btn btn-primary" id="btn-submit-update-data">Update</button>
                        <button type="reset" class="btn btn-info">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const domContentListUser = document.querySelector("#content-user");
    const btnSubmitData = document.querySelector("#btn-submit-data");
    const btnSubmitUpdateData = document.querySelector("#btn-submit-update-data");
    const titleActionDom = document.querySelector("#title-action");
    const formDataInput = document.querySelector("#form-input-data")


    window.onload = () => {

        callApiListUser().then(res => res.json()).then(data => {
            loadDomContentListUser(data.data.users);
        })
    }

    const loadDomContentListUser = rest => {
        rest.forEach((element, index) => {
            domContentListUser.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${element.name}</td>
                    <td>${element.email}</td>
                    <td>
                        <button type="button" data-id="${element.id}" class="btn-edit btn btn-sm btn-warning">Edit</button>
                        <button type="button" data-id="${element.id}" class="btn-delete btn btn-sm btn-danger">Delete</button>
                    </td>
                </tr>`
        })

        const domRowTableContent = document.querySelectorAll('#content-user tr');
        domRowTableContent.forEach((element) => {
            let buttons = element.querySelectorAll('button');
            let [edit, remove] = buttons;
            edit.onclick = (event) => {
                let id = event.target.getAttribute('data-id');
                callApiDetailUser(id).then(res => res.json()).then(data => {
                    let user = data.data.user;
                    formDataInput.elements.id.value = user.id
                    formDataInput.elements.name.value = user.name
                    formDataInput.elements.email.value = user.email

                })
            }
            remove.onclick = (event) => {
                let id = event.target.getAttribute('data-id')
                if (confirm("Bạn có chắc muốn xóa không ???")) {
                    callApiDeleteUser(id);
                }
            }
        });
    }

    const callApiListUser = () => {
        return fetch('http://127.0.0.1:8000/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: `{
                    users {
                        id,
                        name,
                        email
                    }
                }`
            })
        })
    }

    const callApiDetailUser = (id) => {
        return fetch('http://127.0.0.1:8000/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: `{
                    user(
                        id:${id}
                    ) {
                        id,
                        name,
                        email
                    }
                }`
            })
        })
    }

    const callApiCreateUser = (obj) => {
        fetch('http://127.0.0.1:8000/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: `mutation{
                    createUser(
                        name: "${obj.name}",
                        email: "${obj.email}",
                        password: "${obj.password}",
                        password_confirmation: "${obj.password_confirmation}"
                    ){
                        name,
                        email
                    }
                }`
            })
        }).then(response => response.json()).then(data => {
            if (data.errors) {
                var errors = data.errors;
                console.log("Lỗi !!!");
                console.log(errors)
            } else {
                alert("Tạo thành công !!!");
                setTimeout(() => {
                    window.location.href = '/'
                }, 1000)
            }
        })
    }

    const callApiUpdateUser = (id, obj) => {
        fetch('http://127.0.0.1:8000/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: `mutation{
                    updateUser(
                        id: ${id},
                        name: "${obj.name}",
                        email: "${obj.email}",
                        ${ obj.password ? `password: "${obj.password}", password_confirmation: "${obj.password_confirmation}"` : '' }
                    ){
                        id,
                        name,
                        email
                    }
                }`
            })
        }).then(response => response.json()).then(data => {
            if (data.errors) {
                var errors = data.errors;
                console.log("Lỗi !!!");
                console.log(errors)
            } else {
                alert("Update thành công !!!");
                setTimeout(() => {
                    window.location.href = '/'
                }, 1000)
            }
        })
    }

    const callApiDeleteUser = (id) => {
        fetch('http://127.0.0.1:8000/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: `mutation{
                    deleteUser(id: ${id})
                }`
            })
        }).then((result) => {
            alert("Xóa thành công !!!")
            setTimeout(() => {
                window.location.href = '/'
            }, 1000)
        }).catch((e) => {
            alert("Lỗi")
        })
    }

    btnSubmitData.onclick = () => {
        const domElement = formDataInput.elements;

        let obj = {
            name: domElement.name.value,
            email: domElement.email.value,
            password: domElement.password.value,
            password_confirmation: domElement.password_confirmation.value
        }
        callApiCreateUser(obj);
    }

    btnSubmitUpdateData.onclick = () => {
        const domElement = formDataInput.elements;
        if (domElement.id.value == "") {
            alert("Bạn chưa chọn người sửa")
            return
        }
        let obj = {
            name: domElement.name.value,
            email: domElement.email.value,
        }
        if (domElement.password.value != ""){
            obj.password = domElement.password.value
            obj.password_confirmation =  domElement.password_confirmation.value
        }

        callApiUpdateUser(domElement.id.value , obj)
    }

</script>
</body>
</html>
