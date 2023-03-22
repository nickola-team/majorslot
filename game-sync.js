const { exec } = require("child_process");


let betwinSync = 60000;
let exitSync = 60000;
let launchSync = 3000;

console.log('======= Starting Game Launch  thread =============');
console.log(' Bet/Win Sync = %d seconds', betwinSync / 1000);
console.log(' LaunchSync Sync = %d seconds', launchSync / 1000);
function gameround()  {
    var datetime = new Date();
    console.log('Syncing bet/win ', datetime.toLocaleString());
  
    var child = exec("php artisan game:sync", (error, stdout, stderr) => {
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
  
    var child = exec("php artisan game:terminate", (error, stdout, stderr) => {
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
  
    var child =  exec("php artisan game:launch", (error, stdout, stderr) => {
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