const { exec } = require("child_process");


let balanceSync = 5000;
let betwinSync = 60000;
console.log('======= Starting pragmatic play balance synchronization thread =============');
console.log(' Balance Sync = %d seconds', balanceSync / 1000);
console.log(' Bet/Win Sync = %d seconds', betwinSync / 1000);
let i = 0;
setInterval(() => {
  var datetime = new Date();
  console.log('Syncing balance ', datetime.toLocaleString());

  exec("php artisan pp:syncbalance 1", (error, stdout, stderr) => {
      if (error) {
          console.log(`error: \n${error.message}`);
          return;
      }
      if (stderr) {
          console.log(`stderr: \n${stderr}`);
          return;
      }
      console.log(`stdout: \n${stdout}`);
  });
}, balanceSync);

setInterval(() => {
  var datetime = new Date();
  console.log('Syncing bet/win ', datetime.toLocaleString());

  exec("php artisan pp:gameround 1", (error, stdout, stderr) => {
      if (error) {
          console.log(`error: \n${error.message}`);
          return;
      }
      if (stderr) {
          console.log(`stderr: \n${stderr}`);
          return;
      }
      console.log(`stdout: \n${stdout}`);
  });
}, betwinSync);