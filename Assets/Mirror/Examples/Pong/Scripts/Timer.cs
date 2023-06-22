using Mirror;
using Mirror.Examples.Pong;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.NetworkInformation;
using System.Net.Sockets;
using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class Timer : NetworkBehaviour
{
    private float startTimer = 120;
    private float currentTimer = 0;
    [SerializeField] TextMeshProUGUI timerText;
    public NetworkManagerGame manager;
    [SyncVar]
    public int n = 0;
    public GameObject gameOver;
    [SerializeField] TextMeshProUGUI p1Score;
    [SerializeField] TextMeshProUGUI p2Score;
    [SerializeField] TextMeshProUGUI winner;
    Player player1;
    Player player2;
    private bool trovato = false;
    private bool procedi = false;
    [SerializeField] TextMeshProUGUI ip;
    
    void Start()
    {
        currentTimer = startTimer;
        gameOver.SetActive(false);
        manager = FindObjectOfType<NetworkManagerGame>();

        //Change name in the ispector for associate the variables player1 and player2 to the two gameObjects. This is usefull to get the correct score for the end
        StartCoroutine(ChangeName());

        if(isServer)
        {
            ip.text = "IP: ";
            List<string> ips = GetLocalIPAddress();
            string str = SystemInfo.operatingSystem;
            if (str.Contains("Android"))
                ip.text = "IP: " + ips[1];
            else ip.text = "IP: " + ips[0];
        }
    }

    public List<string> GetLocalIPAddress()
    {
        var host = Dns.GetHostEntry(Dns.GetHostName());
        List<string> ips = new List<string>();
        foreach (var ip in host.AddressList)
        {
            if (ip.AddressFamily == AddressFamily.InterNetwork)
            {
                ips.Add(ip.ToString());
            }
        }
        return ips;
        throw new System.Exception("No network adapters with an IPv4 address in the system!");
    }

    IEnumerator ChangeName()
    {
        yield return new WaitForSeconds(1);
        if (isClientOnly)
        {
            chanegPlayer1();
            player2 = GameObject.Find("Skull(Clone)").GetComponent<Player>();
        }
        else
        {
            chanegPlayer1();
            procedi = true;
        }      
    }

    void FixedUpdate()
    {
        if (isServer && !trovato && procedi)
        {
            try
            {
                player2 = GameObject.Find("Skull(Clone)").GetComponent<Player>();
                trovato = true;
            }
            catch
            {
            }
        }

        //make a control to know when timer has to start. He will start when all players are ready
        if (manager.canGo)
        {
            n = 1;
        }

        if (n == 1)
        {
            if (currentTimer > 0)
            {
                currentTimer -= Time.fixedDeltaTime;
                timerText.text = currentTimer.ToString("0");
            }
            else
            {
                p1Score.text = "Player1: " + player1.score;
                p2Score.text = "Player2: " + player2.score;
                if(player1.score > player2.score)
                    winner.text = "The winner is Player1";
                else if(player1.score < player2.score)
                    winner.text = "The winner is Player2";
                else winner.text = "Both players win";

                if (isServer && gameOver.activeInHierarchy == false)
                {
                    eleiminaPlayers();
                }
            }
        }
    }

    [ClientRpc]
    public void eleiminaPlayers()
    {
        manager.setFineGioco();
        gameOver.SetActive(true);
    }

    private void chanegPlayer1()
    {
        player1 = GameObject.Find("Skull(Clone)").GetComponent<Player>();
        player1.name = "Skull(Clone)1";
    }
}
