const { exec } = require("child_process");


let balanceSync = 30;
let betwinSync = 30;
let launchSync = 2000;
console.log('======= Starting pragmatic play balance synchronization thread =============');
console.log(' Balance Sync = %d seconds', balanceSync / 1000);
console.log(' Bet/Win Sync = %d seconds', betwinSync / 1000);
let i = 0;
function syncBalance()  {
    var datetime = new Date();
    console.log('Syncing balance ', datetime.toLocaleString());
  
    var child = exec("php artisan pp:syncbalance 1", (error, stdout, stderr) => {
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
        console.log('exit syncBalance');
        // setTimeout(syncBalance, balanceSync);
        makeurl();
    });
    child.on('error', (code, signal) => {
      console.log('error syncBalance');
        makeurl();
        // setTimeout(syncBalance, balanceSync);
      });
  };
function gameround()  {
    var datetime = new Date();
    console.log('Syncing bet/win ', datetime.toLocaleString());
  
    var child = exec("php artisan pp:gameround 1", (error, stdout, stderr) => {
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

function makeurl() {
    var datetime = new Date();
    console.log('making launch', datetime.toLocaleString());
  
    var child =  exec("php artisan launch:makeurl", (error, stdout, stderr) => {
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
        // setTimeout(makeurl, launchSync);
         setTimeout(syncBalance, balanceSync);
        
    });
    child.on('error', (code, signal) => {
        console.log('error launchSync');
        //setTimeout(makeurl, launchSync);
         setTimeout(syncBalance, balanceSync);
    })
}

setTimeout(syncBalance , balanceSync);
setTimeout( gameround, betwinSync);
// setTimeout(makeurl, launchSync);