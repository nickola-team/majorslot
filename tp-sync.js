const { exec } = require("child_process");


let betwinSync = 10000;
let exitSync = 1000;
let launchSync = 300;

console.log('======= Starting Theplus bet/win synchronization thread =============');
console.log(' Bet/Win Sync = %d seconds', betwinSync / 1000);
console.log(' LaunchSync Sync = %d seconds', launchSync / 1000);
function gameround()  {
    var datetime = new Date();
    console.log('Syncing bet/win ', datetime.toLocaleString());
  
    var child = exec("php artisan theplus:sync", (error, stdout, stderr) => {
        if (error) {
            console.log(`error: \n${error.message}`);
            return;
        }
        if (stderr) {
            console.log(`stderr: \n${stderr}`);
            return;
        }
        console.log(`sync result: \n${stdout}`);
    });
    child.on('close', (code, signal) => {
      console.log('exit gameround');
      setTimeout(gameround, betwinSync);
      });
      child.on('error', (code, signal) => {
      console.log('error gameround');
      setTimeout(gameround, betwinSync);
      });
  }

function terminateBalance()  {
    var datetime = new Date();
    console.log('Syncing exit player balance ', datetime.toLocaleString());
  
    var child = exec("php artisan theplus:terminate", (error, stdout, stderr) => {
        if (error) {
            console.log(`error: \n${error.message}`);
            return;
        }
        if (stderr) {
            console.log(`stderr: \n${stderr}`);
            return;
        }
        console.log(`sync result: \n${stdout}`);
    });
    child.on('close', (code, signal) => {
      console.log('exit exitSync');
      setTimeout(terminateBalance, exitSync);
      });
      child.on('error', (code, signal) => {
      console.log('error exitSync');
      setTimeout(terminateBalance, exitSync);
      });
  }

function makeurl() {
    var datetime = new Date();
    console.log('making launch', datetime.toLocaleString());
  
    var child =  exec("php artisan theplus:launch", (error, stdout, stderr) => {
        if (error) {
            console.log(`error: \n${error.message}`);
            return;
        }
        if (stderr) {
            console.log(`stderr: \n${stderr}`);
            return;
        }
    });
    
    child.on('close', (code, signal) => {
        console.log('exit launchSync');
        setTimeout(makeurl, launchSync);
    });
    child.on('error', (code, signal) => {
        console.log('error launchSync');
        setTimeout(makeurl, launchSync);
    })
}

setTimeout( gameround, betwinSync);
setTimeout(makeurl, launchSync);
setTimeout(terminateBalance, exitSync);