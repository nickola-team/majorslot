const { exec } = require("child_process");
var bbPromise = require('bluebird');

let launchSync = 200;

console.log('======= Starting PowerBall  thread =============');

function putdbProcess(gameid)
{
    return new bbPromise(function(resolve, reject) {
        var process = exec(`php artisan pbgame:process ${gameid}`, {cwd: '../'}, (error, stdout, stderr) => {
            if (error) {
                console.log(`putdbproc error: \n${error.message}`);
                return;
            }
            if (stderr) {
                console.log(`putdbproc stderr: \n${stderr}`);
                return;
            }
            console.log(stdout);
        });
  
        process.on('exit', function() {
          resolve();
        });
      });
}

function processRound() {
    var datetime = new Date();
    console.log('processing powerball round', datetime.toLocaleString());
  
    var child =  exec("php artisan pbgame:list", {cwd: '../'}, (error, stdout, stderr) => {
        if (error) {
            console.log(`error: \n${error.message}`);
            return;
        }
        if (stderr) {
            console.log(`stderr: \n${stderr}`);
            return;
        }
        const gamelist = JSON.parse(stdout);
        console.log("game list: : " + JSON.stringify(gamelist));
        var commands = gamelist.map(function(value) {
            console.log("putting game id :  " + value['id']);
            return putdbProcess.bind(null, value['id']);
          });

        return bbPromise.map(commands, function(command) {
            return command();
          })
          .then(function() {
            console.log('All Job completed');
            setTimeout(processRound, launchSync);
          });
    });
    
    child.on('error', (code, signal) => {
        console.log('error processRound');
        setTimeout(processRound, launchSync);
    })
}

setTimeout(processRound, launchSync);