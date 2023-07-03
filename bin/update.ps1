$env:GIT_TERMINAL_PROMPT = 0
$apphome = "C:/Users/jpedr/Documents/mtg-stats"
$logfile = "$apphome/log/update.log"
git pull 2>&1 >> $logfile
