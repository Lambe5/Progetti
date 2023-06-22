using System.Collections;
using System.Collections.Generic;
using TMPro;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using Mirror;

/*
Documentation: https://mirror-networking.gitbook.io/docs/components/network-manager
API Reference: https://mirror-networking.com/docs/api/Mirror.NetworkManager.html
*/


public class NetworkManagerGame : NetworkManager
{
    public Transform leftSpawn;
    public Transform rightSpawn;
    GameObject coin;
    GameObject ostacolo;
    GameObject power;
    private bool semaforo = true;
    private int powerChoose = 0;
    public bool fine = false;
    GameObject player;
    public List<GameObject> players = new List<GameObject>();
    public bool canGo = false;
    public GameObject menu;
    public GameObject exit;
    private List<GameObject> ostacoli = new List<GameObject>();
    private bool fineGioco = false;


    public override void OnServerAddPlayer(NetworkConnectionToClient conn)
    {
        // add player at correct spawn position
        Transform start = numPlayers == 0 ? leftSpawn : rightSpawn;
        player = Instantiate(playerPrefab, start.position, start.rotation);
        players.Add(player);
        NetworkServer.AddPlayerForConnection(conn, player);

        // spawn ball if two players
        if (numPlayers == 2)
        {
            canGo = true;
            ostacolo = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Ostacolo"), new Vector2(0, 0), Quaternion.identity);
            NetworkServer.Spawn(ostacolo);
            ostacoli.Add(ostacolo);

            ostacolo = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Ostacolo"), new Vector2(-17, 8), Quaternion.identity);
            NetworkServer.Spawn(ostacolo);
            ostacoli.Add(ostacolo);

            ostacolo = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Ostacolo"), new Vector2(-17, -8), Quaternion.identity);
            NetworkServer.Spawn(ostacolo);
            ostacoli.Add(ostacolo);

            ostacolo = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Ostacolo"), new Vector2(17, 8), Quaternion.identity);
            NetworkServer.Spawn(ostacolo);
            ostacoli.Add(ostacolo);

            ostacolo = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Ostacolo"), new Vector2(17, -8), Quaternion.identity);
            NetworkServer.Spawn(ostacolo);
            ostacoli.Add(ostacolo);
        }
    }

    private void Awake()
    {
        AudioListener.volume = 1;
    }

    private void FixedUpdate()
    {
        if (numPlayers == 1)
        {
            exit.SetActive(true);
        }
        else if (numPlayers == 2 && exit.activeInHierarchy)
        {
            exit.SetActive(false);
        }

        if (numPlayers == 2 && semaforo && !fine)
        {
            semaforo = false;
            StartCoroutine(CoinCoroutine());
            StartCoroutine(PowerCoroutine());
        }

        if (fineGioco)
        {
            exit.SetActive(true);
            for (int i = 0; i < ostacoli.Count; i++)
            {
                NetworkServer.Destroy(ostacoli[i]);
            }
            for (int i = 0; i < players.Count; i++)
            {
                NetworkServer.Destroy(players[i]);
            }
            fine = true;
            StopAllCoroutines();
        }
    }

     IEnumerator CoinCoroutine()
     {
         yield return new WaitForSeconds(3);
         for(int i = 0; i < 3; i++)
         {
             Vector3 spawnPosition = new Vector3(Random.Range(-21, 21), Random.Range(-13, 13), 1);
             coin = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Coin"), spawnPosition, Quaternion.identity);
             NetworkServer.Spawn(coin);
         }
         semaforo = true;
     }

    IEnumerator PowerCoroutine()
    {
        yield return new WaitForSeconds(15);
        Vector3 spawnPosition = new Vector3(Random.Range(-21, 21), Random.Range(-13, 13), 1);

        powerChoose = Random.Range(0, 7);

        if (powerChoose != 0 && powerChoose >= 5)
            power = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Power1"), spawnPosition, Quaternion.identity);
        else if (powerChoose != 0 && powerChoose < 5)
            power = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Power2"), spawnPosition, Quaternion.identity);
        else if (powerChoose == 0)
            power = Instantiate(spawnPrefabs.Find(prefab => prefab.name == "Power3"), spawnPosition, Quaternion.identity);

        NetworkServer.Spawn(power);
    }

    public override void OnServerDisconnect(NetworkConnectionToClient conn)
    {
        StopHost();
        SceneManager.LoadScene(SceneManager.GetActiveScene().name);
        base.OnServerDisconnect(conn);
    }

    [System.Obsolete]
    public override void OnClientDisconnect(NetworkConnection conn)
    {
        SceneManager.LoadScene(SceneManager.GetActiveScene().name);
        base.OnClientDisconnect(conn);
    }

    public void setFineGioco()
    {
        fineGioco = true;
    }
}
