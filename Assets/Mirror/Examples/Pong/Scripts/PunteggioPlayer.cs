using Mirror.Examples.Pong;
using UnityEngine;

namespace Mirror.Examples.MultipleAdditiveScenes
{
    public class PunteggioPlayer : NetworkBehaviour
    {
        private Player p;

        public void Awake()
        {
            p = GetComponent<Player>();
        }

        void OnGUI()
        {            
            if (isLocalPlayer)
            {
                if (isServer)
                {
                    createBox("Score Player1: ");
                }
                else
                {
                    createBox("Score Player2: ");
                }

            }
        }

        private void createBox(string str)
        {
            GUI.Box(new Rect(100f, 10f, 500f, 100f), str + $"{p.score}");
            GUI.skin.box.fontSize = 60;
            GUI.skin.box.fontStyle = FontStyle.Bold;
        }
    }
}
