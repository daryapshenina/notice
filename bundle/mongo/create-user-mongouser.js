db.createUser(
    {
        user: "mongouser",
        pwd: "Qwerty123",
        roles: [
            "readWriteAnyDatabase",
            "userAdminAnyDatabase",
            "dbAdminAnyDatabase"
        ]
    }
);