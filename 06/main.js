#!/usr/bin/env node

var net = require('net');
var util = require('util');
var crypto = require('crypto');
var readline = require('readline');

var inputKeyPhrase;
var dh, secret, state = 0;
var serverPublicKey, serverDh, serverSecret;
var clientPrime, clientPublicKey, clientSecret;

var options = {
	'port': 6969,
	'host': '54.83.207.90',
};
var socket = net.connect(options);

var rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});
rl.on('line', function(line){
    inputKeyPhrase = line;
});

socket.on('data', function(data) {
	data = data.toString().trim().split('|');

        if (data[0].indexOf('SERVER->CLIENT:') != -1) {
            /**
             * Inside this block the program acts as server with the data 
             * obtained on the client->server communication,
             */
            data[0] = data[0].replace('SERVER->CLIENT:', '');
            if (state == 1) {
                //Server's faked response.
                socket.write('hello!');
            } else if (state == 2 && data[0] == 'key') {
                serverPublicKey = data[1]; //PublicKey stolen from the server.
                
                //Faked communication with the client
                serverDh = crypto.createDiffieHellman(clientPrime, 'hex');
                serverDh.generateKeys();
                serverSecret = serverDh.computeSecret(clientPublicKey, 'hex');
                socket.write(util.format('key|%s\n', serverDh.getPublicKey('hex')));
            } else if (state == 3 && data[0] == 'result') {
                var decipher = crypto.createDecipheriv('aes-256-ecb', secret, '');
		var message = decipher.update(data[1], 'hex', 'utf8') + decipher.final('utf8');
		console.log(message);
                socket.end();
            }
        } else if (data[0].indexOf('CLIENT->SERVER:') != -1) {
            /**
             * Here the program acts as client, stealing the request data to 
             * create fake responses of the server and replacing the requests 
             * with the ones created by the program.
             */
            data[0] = data[0].replace('CLIENT->SERVER:', '');
            if (state == 0) {
                //Initial passthru communication.
                socket.write('hello?');
                state++;
            } else if (state == 1 && data[0] == 'key') {
                clientPrime = data[1]; //Prime stolen from the client request.
                clientPublicKey = data[2]; //Public key stolen from the client request.
                
                //Init of the attack.
                dh = crypto.createDiffieHellman(256);
		dh.generateKeys();
		socket.write(util.format('key|%s|%s\n', dh.getPrime('hex'), dh.getPublicKey('hex')));
                state++;
            } else if (state == 2 && data[0] == 'keyphrase') {
                secret = dh.computeSecret(serverPublicKey, 'hex');
		var cipher = crypto.createCipheriv('aes-256-ecb', secret, '');
		var keyphrase = cipher.update(inputKeyPhrase, 'utf8', 'hex') + cipher.final('hex');
		socket.write(util.format('keyphrase|%s\n', keyphrase));
                state++;
            }
        }
});