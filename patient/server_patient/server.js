require("dotenv").config();
const express = require("express");
const router = require("./controller/router");
const cors = require("cors");
const app = express();

app.use(express.json());
app.use(cors());
app.use("/api", router);

app.get("/api", (req, res) => {
    res.send("This is rest apis for hospital information system.");
});

const port = process.env.APP_PORT || 5000;
app.listen(port, () => console.log(`Server started on port ${port}`));