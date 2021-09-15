module.exports = { responseJSON, getPostData };

function responseJSON(responseObj, statusCode, messageObj) {
    const headers = {
        "Access-Control-Allow-Origin": "*",
        "Access-Control-Allow-Methods": "OPTIONS, POST, GET, DELETE",
        "Access-Control-Max-Age": 2592000,
        "Content-Type": "application/json",
    };
    responseObj.writeHead(statusCode, headers);
    responseObj.end(JSON.stringify(messageObj));
}

function getPostData(req) {
    return new Promise((resolve, reject) => {
        try {
            let body = "";

            req.on("data", (chunk) => {
                body += chunk.toString();
            });

            req.on("end", () => {
                resolve(JSON.parse(body));
            });
        } catch (error) {
            reject(error);
        }
    });
}