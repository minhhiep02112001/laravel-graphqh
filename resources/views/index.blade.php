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
                    <form class="row mb-3" id="form-input-search">

                        <div class="col">
                            <input type="text" class="form-control" id="search-name" placeholder="Search name ...">
                        </div>

                        <div class="col">
                            <input type="text" class="form-control" id="search-email" placeholder="Search email ...">
                        </div>

                        <div class="col">
                            <button type="button" class="btn btn-primary" id="btn-search-data">Search</button>
                            <button type="reset" class="btn btn-warning" id="btn-reset-search-data">Cancel</button>
                        </div>

                    </form>
                    <div style="max-height: 500px; overflow: auto">
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
                    <h2 id="title-action">Th??m User</h2>
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
    const formDataInputSearch = document.querySelector("#form-input-search")
    const btnSubmitSearch = document.querySelector("#btn-search-data")
    const btnResetSearch = document.querySelector("#btn-reset-search-data")

    window.onload = () => {
        callApiListUser().then(res => res.json()).then(data => {
            loadDomContentListUser(data.data.users);
        })
    }

    const loadDomContentListUser = rest => {
        domContentListUser.innerHTML = '';
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
                if (confirm("B???n c?? ch???c mu???n x??a kh??ng ???")) {
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
                console.log("L???i !!!");
                console.log(errors)
            } else {
                alert("T???o th??nh c??ng !!!");
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
                        ${obj.password ? `password: "${obj.password}", password_confirmation: "${obj.password_confirmation}"` : ''}
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
                console.log("L???i !!!");
                console.log(errors)
            } else {
                alert("Update th??nh c??ng !!!");
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
            alert("X??a th??nh c??ng !!!")
            setTimeout(() => {
                window.location.href = '/'
            }, 1000)
        }).catch((e) => {
            alert("L???i")
        })
    }

    const callApiSearchUser = (obj) => {
        let str = '';
        for (const strKey in obj) {
            str += `${strKey}: "${obj[strKey]}", `
        }
        return fetch('http://127.0.0.1:8000/graphql', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                query: `mutation{
                    searchUser(
                        ${str}
                    ){
                        id,
                        name,
                        email
                    }
                }`
            })
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
            alert("B???n ch??a ch???n ng?????i s???a")
            return
        }
        let obj = {
            name: domElement.name.value,
            email: domElement.email.value,
        }
        if (domElement.password.value != "") {
            obj.password = domElement.password.value
            obj.password_confirmation = domElement.password_confirmation.value
        }

        callApiUpdateUser(domElement.id.value, obj)
    }

    btnSubmitSearch.onclick = () => {
        const domElement = formDataInputSearch.elements;
        var obj = {}
        if (domElement['search-name'].value) {
            obj.name = domElement['search-name'].value
        }

        if (domElement['search-email'].value) {
            obj.email = domElement['search-email'].value
        }

        if (!(JSON.stringify(obj) === '{}')) {
            callApiSearchUser(obj).then(res => res.json()).then(data => {
                loadDomContentListUser(data.data.searchUser);
            })
        }
    }

    btnResetSearch.onclick = () => {
        callApiListUser().then(res => res.json()).then(data => {
            loadDomContentListUser(data.data.users);
        })
    }

</script>
</body>
</html>
