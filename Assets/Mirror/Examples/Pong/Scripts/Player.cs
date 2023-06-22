using System.Collections;
using TMPro;
using UnityEngine;

namespace Mirror.Examples.Pong
{
    public class Player : NetworkBehaviour
    {
        private float speed = 20;
        private float fastSpeed = 35;
        public Rigidbody2D rigidbody2d;
        private Vector3 pos;
        [SyncVar]
        public int score;
        public int oldScore;
        private AudioSource[] audioArray;
        private AudioSource coinAudio;
        private AudioSource powerAudio;

        private void Start()
        {
            audioArray = GetComponents<AudioSource>();
            coinAudio = audioArray[0];
            powerAudio = audioArray[1];
            rigidbody2d = GetComponent<Rigidbody2D>();
            if (isLocalPlayer)
                rigidbody2d.simulated = true;
            oldScore = 0;
        }

        // need to use FixedUpdate for rigidbody
        void FixedUpdate()
        {
            if (isLocalPlayer && Input.touchCount > 0)
            {
                pos = Camera.main.ScreenToWorldPoint(new Vector2(Input.GetTouch(0).position.x, Input.GetTouch(0).position.y));

                if (gameObject.GetComponent<SpriteRenderer>().color == new Color(1f, 1f, 1f, .99f))
                    transform.position = Vector3.MoveTowards(transform.position, new Vector2(pos.x, pos.y), fastSpeed * Time.deltaTime);
                else
                    transform.position = Vector3.MoveTowards(transform.position, new Vector2(pos.x, pos.y), speed * Time.deltaTime);
            }

            //Client comunicate to Server his score. Score is a SyncVar so everytime that Host update his score the Client update the score of host player too.
            //That is possible because SyncVarwork from Server to Client so for make a specular behaviour I use Comand
            if(score > oldScore)
            {
                if (isClientOnly)
                {
                    aggiornaScoreCmd();
                }
                oldScore++;
            }
        }

        [Command]
        public void aggiornaScoreCmd()
        {
            score += 1;
        }

        [Command]
        public void cambiaColoreCmd()
        {
            colorChange();
        }

        [ClientRpc]
        public void cambiaColoreRpc()
        {
            colorChange();
        }

        [ClientRpc]
        void restoreServer()
        {
            StopAllCoroutines();
            StartCoroutine(ExampleCoroutine());
        }

        [Command]
        void restoreClientCmd()
        {
            StopAllCoroutines();
            StartCoroutine(ExampleCoroutine());
        }

        void restoreClient()
        {
            StopAllCoroutines();
            StartCoroutine(ExampleCoroutine());
        }

        public void OnCollisionEnter2D(Collision2D collision)
        {
            if(collision.gameObject.name != "Coin(Clone)" && collision.gameObject.name != "Ostacolo(Clone)")
            {
                //the timer start to make finish the power-up effect
                if (isServer)
                    restoreServer();
                else
                {
                    restoreClient();
                    restoreClientCmd();
                }

                //code to comunicate the color change
                if (collision.gameObject.name == "Power3(Clone)")
                {
                    if (isClientOnly)
                    {
                        colorChange();
                        cambiaColoreCmd();
                    }
                    else cambiaColoreRpc();
                }

            }

            if(collision.gameObject.name == "Coin(Clone)")
            {
                coinAudio.Play();
            }

            if(collision.gameObject.name == "Power1(Clone)" || collision.gameObject.name == "Power2(Clone)" || collision.gameObject.name == "Power3(Clone)")
            {
                powerAudio.Play();
            }
        }

        IEnumerator ExampleCoroutine()
        {
            yield return new WaitForSeconds(5);
            gameObject.transform.localScale = new Vector3(6, 6f, 1);
            gameObject.GetComponent<SpriteRenderer>().color = new Color(1f, 1f, 1f, 1f);
        }

        private void colorChange()
        {
            gameObject.GetComponent<SpriteRenderer>().color = new Color(1f, 1f, 1f, .99f);
            transform.localScale = new Vector3(6f, 6f, 1);
        }
    }
}
