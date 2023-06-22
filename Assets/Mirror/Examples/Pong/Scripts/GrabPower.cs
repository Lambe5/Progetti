using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using Mirror;
using Mirror.Examples.Pong;

public class GrabPower : NetworkBehaviour
{
    private void OnCollisionEnter2D(Collision2D collision)
    {
        if(collision.gameObject.name == "Skull(Clone)" || collision.gameObject.name == "Skull(Clone)1")
        {
            if (isClientOnly)
            {
                powerCmd(collision.gameObject);
                power(collision.gameObject);
            }
            else powerRpc(collision.gameObject);
        }
    }
    [Command(requiresAuthority = false)]
    void powerCmd(GameObject p)
    {
        power(p);

        if (gameObject != null)
            NetworkServer.Destroy(gameObject);
    }

    [ClientRpc]
    void powerRpc(GameObject p)
    {
        power(p);

        if (gameObject != null)
            NetworkServer.Destroy(gameObject);
    }

    private void power(GameObject player)
    {
        switch (gameObject.name)
        {
            case "Power1(Clone)":
                player.transform.localScale = new Vector3(3f, 3f, 1);
                player.GetComponent<SpriteRenderer>().color = new Color(1f, 1f, 1f, 1f);
                break;
            case "Power2(Clone)":
                player.transform.localScale = new Vector3(10f, 10f, 1);
                player.GetComponent<SpriteRenderer>().color = new Color(1f, 1f, 1f, 1f);
                break;
        }
    }
}
