db.createUser(
    {
        user:"fluented",
        pwd:"Qwerty123",
        roles:[
            {
                role:"readWrite",
                db:"log"
            }
        ]
    }
);