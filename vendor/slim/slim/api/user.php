<?php

$app->get('/user', function ($request, $response, $args) {
    $sth = getConnection()->prepare("SELECT * FROM user ORDER BY id");
    $sth->execute();
    $todos = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $response->withJson($todos);
});

$app->get('/user/[{id}]', function ($request, $response, $args) {
    $sth = getConnection()->prepare("SELECT * FROM user WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    $todos = $sth->fetchObject();
    return $response->withJson($todos);
});


$app->get('/user/search/[{query}]', function ($request, $response, $args) {
    $sth = getConnection()->prepare("SELECT * FROM user WHERE name LIKE :query ORDER BY name");
    $query = "%".$args['query']."%";
    $sth->bindParam("query", $query);
    $sth->execute();
    $todos = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $response->withJson($todos);
});

$app->post('/user', function ($request, $response) {
    $input = $request->getParsedBody();

    $sql = "INSERT INTO `user` (`name`,`email`,`phone`) VALUES (:name,:email,:phone)";
    $sth = getConnection()->prepare($sql);
    $sth->bindParam("name", $input['name']);
    $sth->bindParam("email", $input['email']);
    $sth->bindParam("phone", $input['phone']);
    $sth->execute();
    $input['id'] = getConnection()->lastInsertId();
    return $response->withJson($input);
});


$app->delete('/user/[{id}]', function ($request, $response, $args) {
    $sth = getConnection()->prepare("DELETE FROM user WHERE id=:id");
    $sth->bindParam("id", $args['id']);
    $sth->execute();
    //   $todos = $sth->fetchAll(PDO::FETCH_ASSOC);
    return 'Deleted';
});

$app->put('/user/[{id}]', function ($request, $response, $args) {
    $input = $request->getParsedBody();
    $sql = "UPDATE user SET name=:name WHERE id=:id";
    $sth = getConnection()->prepare($sql);
    $sth->bindParam("id", $args['id']);
    $sth->bindParam("name", $input['name']);
    $sth->execute();
    $input['id'] = $args['id'];
    return $response->withJson($input);
});