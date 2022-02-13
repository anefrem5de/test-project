<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Test</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-3"></div>
        <div id="content" class="col-6">
            <div id="order_form__wrapper">
                <form id="order_form" name="testForm" action="{{route("order-form-send")}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="order_form__full_name">Last name, first name, patronymic name</label>
                        <input id="order_form__full_name"
                               type="text" class="form-control"
                               name="full_name"
                               value="{{$full_name}}">
                    </div>
                    <div class="form-group">
                        <label for="order_form__article">Article</label>
                        <input id="order_form__article"
                               type="text" class="form-control"
                               name="article"
                               value="{{$article}}">
                    </div>
                    <div class="form-group">
                        <label for="order_form__brand">Brand</label>
                        <input id="order_form__brand"
                               type="text" class="form-control"
                               name="brand"
                               value="{{$brand}}">
                    </div>
                    <div class="form-group">
                        <label for="order_form__comment">Comment</label>
                        <textarea id="order_form__comment"
                                  class="form-control"
                                  name="comment"
                                  rows="3">{{$comment}}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <input id="order_form__submit_btn"
                               type="submit"
                               class="btn btn-primary mt-3 col-12"
                               value="Send">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-3"></div>
    </div>
</div>


</body>
</html>
