#!/bin/bash

SESSION_NAME="my_session"

# Check if the session already exists
if tmux has-session -t $SESSION_NAME 2>/dev/null; then
    echo "Session $SESSION_NAME already exists. Attaching to it."
    tmux attach-session -t $SESSION_NAME
else
    # Create a new session and name it
    tmux new-session -d -s $SESSION_NAME

    # Split the window horizontally
    tmux split-window -h

    # Send a command to the first pane
    tmux send-keys -t 0 'docker exec -it mongo:latest /bin/bash' C-m

    # Send a command to the second pane
    tmux send-keys -t 1 'echo "Hello from pane 2"' C-m

    # Attach to the created session
    tmux attach-session -t $SESSION_NAME
fi

