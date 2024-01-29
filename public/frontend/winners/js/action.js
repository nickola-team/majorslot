function startGame(gamename) {
    if (loginYN !== 'Y') {
        showLoginAlert()
        return;
    }

    window.open(
        "/game/" + gamename,
        gamename,
        "width=1280, height=742, left=100, top=50"
    );
}