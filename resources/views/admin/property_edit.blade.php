@extends('layouts.app')

@section('content')
<style>
:root {
    --primary: #00a651;
    --secondary: #007a42;
    --bg: #f5f7fa;
    --card-bg: #fff;
    --text-dark: #222;
    --text-muted: #6b7280;
    --radius: 12px;
}
body {
    background-color: var(--bg);
}
.card {
    border-radius: var(--radius);
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
}
.btn-primary {
    background-color: var(--primary);
    border: none;
}
.btn-primary:hover {
    background-color: var(--secondary);
}
</style>

<div class="container py-5">
    <div class="card p-4">
        <h2 class="fw-bold text-success mb-4">
            <i class="bi bi-pencil-square"></i> Edit Property
        </h2>

        <form id="editPropertyForm" onsubmit="handleUpdate(event)">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control" id="title">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Property Type</label>
                    <select class="form-select" id="property_type">
                        <option value="Apartment">Apartment</option>
                        <option value="House">House</option>
                        <option value="Condo">Condo</option>
                        <option value="Townhouse">Townhouse</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Monthly Rent ($)</label>
                    <input type="number" class="form-control" id="monthly_rent">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bedrooms</label>
                    <input type="number" class="form-control" id="bedrooms">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bathrooms</label>
                    <input type="number" class="form-control" id="bathrooms">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">City</label>
                    <input type="text" class="form-control" id="city">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea class="form-control" rows="4" id="description"></textarea>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-4">Update Property</button>
            </div>
        </form>
    </div>
</div>

{{-- ✅ SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// 🏠 Sample properties array (can come from JSON or another script)
const properties = [
    {
        "property_id": 10001,
        "title": "Charming 2-Bedroom Duplex with Backyard",
        "property_type": "House",
        "monthly_rent": 2100,
        "bedrooms": 2,
        "bathrooms": 1,
        "city": "Springfield",
        "description": "Contemporary 2-bedroom luxury villa with smart-home automation, marble flooring, and panoramic views of the city skyline.",
        "image_url": "data:image\/jpeg;base64,\/9j\/4AAQSkZJRgABAQAAAQABAAD\/2wCEAAkGBxMSEhUTExMWFhUXGBkYGBcXGBoeHRkXHRgXFxcYGh8dHSogGBolGxcXITEhJSkrLi4uFx8zODMsNygtLisBCgoKDg0OGxAQGy0lICU1LTAtLS8tLS0tLi0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf\/AABEIAMUBAAMBIgACEQEDEQH\/xAAcAAACAgMBAQAAAAAAAAAAAAAFBgMEAAIHAQj\/xABMEAABAwEFAwkEBgcGBAcBAAABAgMRAAQFEiExBkFREyIyYXGBkaGxB8HR8BQjQlJi4SQzQ3KCkrIVc7PC0vEWJWOiNFN0k6PD00T\/xAAZAQADAQEBAAAAAAAAAAAAAAABAgMEAAX\/xAAuEQACAgEEAQMBCAIDAAAAAAAAAQIRAxIhMUFRBBMiYTJxgZGhscHwFNFSYuH\/2gAMAwEAAhEDEQA\/AC+KsxVkVmCvWPOMmtkGvMFbJTQOJU1JNRoFWmkTSsKITNXLM3lUdpebaQVrMAeJPAddId8bSq5VJDvJqzwpCo5vXx76z5cqii2PE5HTm2UnKt0skHKYrn127duIIDyEuDinmq+B8qcLq2ostohKXghR+w5zT2CcldxNSWVSKPFJBVtQJiN9XCF7q0RZoOc0QayGtc2ckUeTWrXX50q7Y3MonSpgagtCwASPSkbsdKtydS43V7jTQ9L8jpZ1u22SSeqhQbNrSsA5HOqaHcUzFbvtnjQ5RKVidOIp4qxWyzaUc3iKGrSAYOnGiLro3aUPcFVhZOVGikZa1rhqWKwJqojNAmtgmtwmt0ooWdREBW2Cpkt165CUlR0SCTlOQEnTM91Cw0RhuvHClAlSgkSBJIAkmEjPeSQB20EvnaxhlPNWnnIC2nEw4hefRIQrENNTAzyMgik+033a7ark2kcki0pwEPKxMrWEgEIU4iASBGBEk5mJk1OWRIdQbHG\/tpG7MHEkhLqMKkodCgHUn7hSD1iY5p1EapVv27eU6s2YqQhWGUrwrIUMiUZQiYgiSMpyJNXLq2TSFjlQX3wklyyOEtrIyGNlePC8ADEzhMiSgiKrbY4QLO2h0qQgrSELRheZ6H1axlOUYThkjerWpSlKikYo6bYbUh0EokFJhSFCFIPBQOYPrumrQRS5ywwh1xzlEJkItzIGNEGCm0IAgAHpGMIMyluKNWa8cOEPYQFRgeR+qcnTP7CjwJg7iafX5F0eAAK2Ca8CalSitpjNAmtgirDaJqdLPVSuR1FVDVe2q0JZQVrMAeJO4DiasW60tsNlxwwkeJO4AbzXJtsdrVLVOWL7CNyBxPE+vZUMmWi2PFqJtrdqzOeavsI3JHE\/OfZXP3nislSiSo5knfWjjhUSpRJJzJO+rNksk5nSsUpds2xj0jxm2LRooxwOngavNXxOSk94+Boi1c+NsKB45EUNtdzLT9nvGdTU0yjxtDJcO2Vps8Bi0HCP2a+cnswq6P8ADFdAub2roMJtTJSfvtZjtKTmB2FVcKWwRW7VtWnfPUc6qpEnE+prh2jYtJhpxChrAPOHak5jvFGHzHrXyfZr2AIOaVDMKSdOsEZini5PaTbGgAXRaEDc5moDqWOdP7001i0dnRZApRKTkd1avtLAIQeqOuk3Z\/2iWZRh0qZJjJXOTP7w0HaBTl9MS4kLaIWCNUKBB7CMqe99ha23KibSoDDqeuobRKoNeKTiVlv+TV1TIiD3GrbInyUEA1uUGvcNbnOnEIsNbJRUiUV6taUxiIBUYSCc1GCYTxMA5DhXNnUeBFY84ltJWshKUiVE6AcTwFLF9bcstCG+crIgEHUGFNuJJC2V9agew6UrPWu22rC4F8g2SeSdWvCVJUrDyIeSlKV5nmpXhmRmdak8ngooDdfm2TNnkCFKBSQkmQ42ROJtaMQSepcTHWDSiq8LdbVgNBSSmVNrJCH1MqIBCSCkPpGvNTGQklUEym4rPZgSpKlOJ5wCgEuDTmrYWcD7cnNbSyTMUXu042ApSUcnIPJmXGG4zTp9dYFjUKzSkEVFzt0OklwDbs2YZTicKlPKSrEXQ2VcmoicNqsqhygGYJMk7+ZrR1ISlv8AZIZcGhPK2B4E7jrZVGNICZVos1MUSUFWIqiGlKcCXY1iz2tPMtCTE8m7mrVVTMoIWspKg5q4ptvC6RpNpsp5toHNjlW8z9mBnRug1ZGpgmGVIUd6bM+vngj7djtIPOIEkAnEJGbYypX26dxKs4LpWUlYhxGF9AlHNdy541wqAg59LUsVptjDDeFSmUtqkhqS5ZnCnchIl2xuAgZAYUnQKVSTtXfwtBaCOVwtA4Q8QpaScMpC9XEjCCCslWZk7gkmMkdDU5E2jlUoJkfTWRLSiCE4bY1PNUCIKpyg85ucNArbtYxZypLSQVlRDjCIVZXgc1LQTBbUc5wjWcSVdKueXztIp1aluulxahJgABUZDEkACRpOuulBF3g45zW0mDAgCSD7qGph0o7yhNWG25rVCKtspr0ZSPOSMbZr222xthouOmEjxJ3JA3k15bralhsuOGEjxJ3AdZrje2+2K3l65jJKRo2Pes\/OUCoZMlF8ePUbbZ7WqdX1\/YRqGxxPFR+csqRVKJMkkk6neTWslR4k+dEbFZN5rHKXbNcY3sjWx2PeaOWOxTmdK2ZsqUpxuEBI4\/OZ6qgdcctBKEJIb4aFQ4qO4dXroI7zZfaCDVkvFgJwlUAE86Dh\/miPOriUBQkKBHEVXsVjBs4TAIiPIdtDG9n3UkkEtn8Mj3wRXLH4OeTi0E7RdyFagT50HtVwg9E9xqZL9qRmQl0eB8vhU7V+oOTiVIPWJHiPyo6ZIGqLFy1XOtO7wqgplSTXQ2XG3BKVBQ4gg\/7VBaLtQrcKKm0c4JiS1eK0659vxoxdO0amVYm3FtK4pJAPbGRHaKntlwA6UItNzLTup1NCODOj3X7RHcuWSl0ffTCVdpjmk9wpwunayy2iAHcKvuOc09gnJXcTXz5gWgyJHZU7V5KHSAPkfhVo5WRliR9OFFepbrg9w7ZPMQG3lJH3F5p7IOQ7orodze0VKoD7WH8beY7cJzA7Caosy7JvE+g7tXfBsjaFJGJS1YUogyvI5JI6KtDMK0iM8kC8V2x5QRaVFlLgTKVkBTiJhJIOFrlBkJPJdmUV0hW0dnKUqQrlCdAgEqB06MYhMgaVUu61ptNqnmjDISFCFAAQ4lUHM5g55Z9RFRyZVdJhiq6FSwXE00QMJU4AMylePLVaEJwvsqzjGwp1OmtFEKjE5iiZC3EqQJO8LdSnkXYySE2lpCsulvrS1XWtLrwWORbCVOJaSlKmzHNStSYUltR5xxJwmQM6q2C9AEJddc0ASHMZKiM\/tJIfSJiU\/XJy6MUizK9I6Vla+SW4ZKVJQSlYb5P6uZ1S0onDAjOzuYSSTA3GLsawNpWcigAFwuGW4jm\/SEDGyMpLdoQUkmJjKla+7+QtIQ2nmhWKTABXmCcKSGlEzONKW18RSzee0hVGNxS4EASTlPRk6xGip0FKr1Nj6djodt2jYaxpBxqVIWlKEQox+3bE2d8E6uNFKt0a0r3tte6sBOINIBBQkKJKFCZLTipcameiCABAG+U9NpfeyaQYz0GWuWZySrsopd+xrrhl1RzzIG\/LidDRcq5GS8A6135JJEqUrMqVqo78XE9db2PlSFFxJSD0QRHaAOGldCuTYVKIITGmZ18TvqHbq6ksGzADpFc9xb+NIsibpBcXVsB3N7O1Lgrk+QHZGdP1z7HstRzAT1DKct2+mq08mykrcUlCU6qWQkDXedKVbx9o1jQcDActK+DSebP75gEdaZqD1zH+MQneLauTUUiSBMTGW\/OeFWrmtSXkYkzlkQeOnuq1YHUuiU9U9U8aq2xhbTiVISMIOaRAnQE5xomeOmlenKW\/uR4PNS6ZR9oCf0FXHEj1r52tDBBMnOfHrrvm19t5SxLB6QcSIjcFQD3jOuM3nZpM1GU1J2jTjj8SpYLMKPkIZAKucs9FA1PwHXQgPFtIIGZ+0dBEeJzots1Y+WViWCZOqtVCJ7hO6o6dT3NGrSqR7ZbE4+oLc0Gg+ynsG89dW7IoIdUkkgEmBGRJme0wkZdtM17WMNLCBpgSe+gr9iDgzmUqJTmRB64IPnVlGqoi3aDF3tjk+oK690UTtVnCxjk4YAKcvu55nq66pXI0SzzoJxZxkCYExRs2MrSW5GUTrlloOrM8KzqXzaLyXwQCstjSrIDLESeIBcXE7jwpetlhl0I4hPmmad7Y2hsLnIpUkc0Z5rVE8RnkTx66VLvbP0hrF+Dww5eVVT2JVuDLTs2QZTII3jIjXeM91QJXaW9FYhwXnp1iD4zXTXrMClXaf89IL+oE8fQ1zSaCm0ysm+oydaUnrHOHx8jVpm0NOdBaT1b\/AA1FA134CrClBUJg56cSTmPnXdUjgYcHRhW7L4ad9c8a6Csr7CtosCVapoTabkG6t2m3kZtrJHAnF65juNSi9Fpycb70\/A\/E0ulobUmBnrnUK1bs9oazSFR+HMeH5U1oUlSAoDIiRVZdswaCpubXBSMIvn9CldW1RbP1iNN6DBHcfjTlcG0TPKBxlwBcyUkwVbiCDrIykUrrt7LuTzST1xB8RnQe\/LtaRhUyolKiZCs8OkR+dSUlJ000\/wBCksdRtNSX6jzfu37i1uwrkgo4Skc4wkxAJEiYExE0oLvdbhhlsqPE5+Q07zRO59kkqMrJWevIfPbT3dOz6EABKRHhVnkS4M6xnOWNmbS\/m4qAdwzPlkKYLq2AQM1JKz+LPy08q6QzdzaE4nFJSkZkqIAHeaF2rbuwNKCGMdpcOiGE4gf4zCSOwml1SkGoo8u\/ZYDdFHkWBphJWspSkDNSiABrqTlSou+r0tL7LCG2rCH8WFZh1wBKSok6JGQiMO+j1l9mVnUoLtjz9sc1+uWQgH8KEnmjqmlarkNg63+0GxNnAwF2pzclhBI\/mMAjrTNJ+1V6Wq1LYU\/Z02dMnkkhRUoyUYio5fhgQN+tNOzFnQ1ZEKgJTKyVZAdNWp8OHbwH7XWZTi7OpI5qVHEtUITmW4hS4CtDoVe6mhV7IV\/U1b2KbWrlLW87alg6uKOEHeAJyz3SmjthsTbJKW0JQAkdERvOZjnDvxCi7N0OrzU4lAzH1crPcpUAfulKh77bVxMjNSeUPFw4gD1J6Ce5IoNvs5UVk3erk3FsqUcZEgEg8CZUdw9PBWvy+uiEvY4CDpASQozEDUAjKDpOeUWtq7wW6lbaQtBSEKwERIIUkKTPOHOxCTGWRziuZf2oVKgmM8wZjSNO3dS5m2\/hx\/ejPFeRv2mtwLaG0LUpGRClRzzMYo4TOQ+9qaSrWijDVpKm4nIkZYR1RB3abuPCqNqao45bGjGtmCX25Yk8feinW7LOErbA4e40quN\/oqj+L3op0sSPrEdh99Wgzprct7T\/AK7+BPvoI2Ne00e2nT9d\/An30EaTke01o7RGtg3ciJbV+97k0XtdpDIXiiVJGEyZnmmBHz11S2cQkNyZ5zkdnRH5+6hu3pUy8M\/q1RuOZ3gnMwInhppWGcJKbaLvIlBIP2VbKytWKVlSZxjSFnfpv40sWlOC0oWrIDB6Vf2LbdDLilghKikSQJBxYUpkEyDAJir9yMrwhBCVyQTmIBMSIM08ZOqYidlO33mopWGxHOOZ7V1ze4UqK1lasRI+Pj212m13QkpMtjX7OXH7uXiK59dVxNF9xttbqSlIUQ4EqHOKgMxhjQ\/Zqt7MG1oSgAMqlYPOHaJ+FELXs5aE\/q+SdzPRcCfJ0IUe4GghYtDTzfLtuNAqEBaFJB7JGdUsQuvWhKXwmecVJGWoBIyojbnl8shvLCqdczli391AXWiq2Dhyjf8Akpsvew4Xm1fvZ96vjSSkPFEl1JVyacpEcPhV1V3pWNK1uVEISdJFHmlzwPbXj5ptSdHtYYpwQpvXEN1DrVcihxroKLHj+yR1jMVqbsTORk\/PzpQj6to6XpoM0vG1t2FoOOIWqThAQBmqCc5MAQNaptX\/AG60nCyluyp+8ocouMhl9kHPhRv2hWQGygAScYjwI99L933ioGGmStUakiNRwMcN4rRKUtFx58syQjC\/l+QLtdyKeWs2l914oWRzlcAMwDITruimO7kNNJDbacPVvO8niqi937O4xjdXCl89SU6BSgCQDw0yM9tHLNdTLfRQOOeffBy8KKc5JamQnOC+yhduhD3LsPIQVBoLyJIEqThjQ4Y6wKcsVpcEqcDaeDYE9hJJ8UkVqlVTlzd876qqIuTIrtuNpqMKAI0O8cczzvOgO3rCcdnOESMceLc03MryEUne0ILC7MrpAY8hvJU2B3a1ogTkN7jYJnfxGR\/OtTI6\/I\/A+VbhYOYORpe2tvJxlAKYAOufd2+Fc1YNVChfeO0ErZIlQcWQ8lGKBzAU6zKTlA3Z51zu1NrSpeLCQlSgCI4kYgT0gYGlPu3d3hFqxWcrCEpIKUqIKZBJKeIk5j5HN7S8VGVGTxnOZBkHjUtm9gJhC7nCXBGYnWOuAe8UWtTVArodBeT3d5kT2flTNaE0stqNWFWmBij9EX\/ef6KcLCPrUdh99Kbg\/Q1\/3n\/5002V7C4kwTA3dYNUxs7ItwptKn63+Ae+gVkTiTlxPrFFbfaeWXiIw5RhGsdZqBKQkQISOAp5Za4BHE3yE7nYPJxvCyr0+FHXXWnEQ42mQITiEwdBHHdvFLLF92dhs41yqTzEiVfAd5FCX9s3FKCWkJQCekoBSszunmpPce2h8pInJJPY6F9MbcaCW1oOJaIAjTGBMeNDblu7k3IJlSlJpOst7uNqkBKp1TGXcBAFFWtr0\/bZI60q9xA9amn5GcDoTiMj2\/GkZKv0t39xHqupWdrmTlyq0fvAn0kVDZHEOWhxSHUEFCIzGsqn1FUU1Qjg7IrXd6FBSgiDrkT6aVWsja0EYSUgnMAkA9o30ffsfNOokbqXWLI6HAVzAIOenwqlp8CpPs8tFls5eGNlBWFoMpRhMyIJKMM981Z2nsCThWCUhIUYgGedpujPrrxaSp+RmMSc\/wCWi20rPNSDoZB\/mNTkOnXYm3MDhGFUgZcPKmFtBSAogZ9WvhrW90XU2lCQlInjv11zPu4VfeRBTkR2V5maCcj0sOdqKRUZtJMwgp668UoyJUdRujz186s2NJJVnv4Z7+FbPM5jLePnjUNKXBVzb5ItvGQGEwIlwSd5yUczqa8ui7ZVl884Vb28QORQP+oPRVWGLcmzBSlpJ0M7kjFCzkCcgQY\/DWrMnJJLuzFjlpTYQaGGBvGWmeWVUry2is7BwuOjFkcIkmIyyGY76y3X3Zkn9eFEHOEqg75GsZ5R1Uq31brE8cRZKlSCT0cQiIJSZOW+N1UiqVEdLk7DjW3dnP2Hct+FI\/zUVuXaRm0KKUBwK15ydw3yCRXN1LZiEsgJmQCpZjKIzVBHaN5qdu1rAwpOFJ+ynIcNE5U6G9o627ejLY+sebT1FQnwBnwode92Ltam1haUtpzSYVJScJmDlu41zEpnifnqrr91fqWv7tH9I+datGTROWNIvNsSMj891B7+uYPpwqkDfh3j3UcZVl8\/nVa0vwNfn3eVL7jixfbUjj+3NlfZVi6SUlQ5pkwRBmNFRh46DspHvOxqGDHCcQJCgCZBzI\/FG4dfbXR7LbFIQAoyCjOdyiZ6XUYGU6jSue3405hSknmTDYyGU5Dz86hgnvSMkX0VLiEPo8uyQO6nJ6ki4ir6QmSTl8IFOT7kZ1fMuD0PT\/ZYIaelBaKRhKyo9emX\/bTRZFwmVGBxNJdntW8eJqV62T0lFXV85UyxvvYLyroZ7VfjaMkDEeO7xoLa7zcXqqBwGVCy+eyvSuIJBE6E7x1cadaY8CNylyWEpJqZpuCDOe7dnVBNtAImSN4Bg9xgx4VA5eXCg5SYVGKGdlWcTnrmQMu\/fwFQTl26UvKvhe4Z\/Pzw6qxq3OrhKcyJ0FDSMnYxOWfL5+e7rrVtoiJEz8+8ULTeTo6Tc\/PWDUjN+FPSQcqFsbSg7Y7QtHRWtI6iR5d48aIpvJyc1Yj+LPSR7qW27\/biIIyjOfhwAq61ezKt6dOI351NtroZQTDrV8lJC1JBgzAMaRxnjVu8Npm3gjmLTBOsEcdx66AqdaUIkDsNQrLaQOf5g+O\/fRWToR4F0Nl029ooSkuQdIUY3nLOr1stKEgKK+bGsiK51\/ajSMiZzn58aq2q8ErPNkz1Hj56VKWLU7KR22H0X20gqlYVnoATx36bjVO1bSj7CDr9ox5CfWk8WlUyAfkzW4fMyY+ewUFhgPqkH732metACVYQkGQEjfmN5J30Pctjq+kpSu0k+GsVRS8BvnuqZNq6jVKEqiXk1HU1MzZ6hQ4o6adVWWh941xxMhsCtwTw+e+vW8O6t1OpTmYHaQPWikcawfmfdXWbtV9S1v8Aq0f0jtrjNpvxhPSfR2A4vSuwXU4FMNEZgtoI7CgdtPVEZsKBeX+9BLytOcD59Y8qIPuQPy\/L30vPuYlT5\/M+oqVXIHRznaG9UtgYYMHPpROkQe+lJV4lZVOeck9oz7KletHKKCSnedQYjdHjG\/PtoYEowrJUAqcknXu86fFhUY78mFRPVrUyvEgjTL3SPfVhu\/315HAZn7J4dRqgxa5Cio5kcB8\/71OyymI38eokVoaXZWMmtkXbPd7igIUnMTBnu01qVm6nSYBQrU4QqCQM96YGQNVLOVjDpE5e+d4nLxotYLeWiXgkHDCXE9U6+RBjOPOE3KxFORFaNmrcln6R9HPIkJUFhbahhXGA81U5yN1B1svb0KHcafGbep0KZCwWWFLQ0EkwrGtTuIx0koSooSDpiPVVW0KSgYlqCRpJO+qJp8GmLtCXyCt4NbIs5O6mlbn1anUsOrbSMRc5MhAHHEqAe6aM2fYu2uaoYZB+8srV4IEf91c2lyUSb4Ehm7idaK3bYMCgo5ZHXspouvYrFanbO9aHCG221y0EtyVlYI0UQBh476zbDZqz2Qsciggr5TEpSlKKoCYkqJjU6RrU3OL+I8U1vRSb3AnWt3EJP2UnuFGrpuKzrZQpTQxESSJBnjka9vO4WUNLWkuApSSPrFR4E15jzQc9O56S1KNtCxarC2R+qHdVP+yGj9kjvpvRsulaEqD6wSkHMIOoB4A1SttxraKAHkqxrCBzSImczCtMqeOZcKX7gai93H9hX\/4dSdFKT89VDrfdBaIBXimfL\/euhIuK0jTArsKvemgF73XaHLSizhocpyZczUkDAVYZntGkVXFnbl9pUTywxqOy3\/EVm2Y3VbbWaa7JsBaVfrHmG+zGs+GFI86NWP2bM\/tLcs\/3SEI\/qK6s82P\/AJIzU10zn0qr0wNSB2mug7H7H2F1tan8TikvOoEurAwJVCZCCATFNLVz3XZ8xZrMkj7SkJJHesT50ss+OLqwLU1aRxRl9KjCCVq4IBUT\/KKL2K5Lc5+rsVoPWpHJ+bmGn68L9sqLws7yHG8CGHUkoIIBJyHNmilp9o93pGS1KPUhX+aBVNV8Im5MRbPsJebkS201\/ePSfBsK9a22U2OftiVrNqS0G3VNEJZxElISSQSoQOdw3UyP+1ZkDmMOE5iTAHUcsVANldqbY024izWNbwW6pwqCFkJUpKeaYgaAHXfTLXXFCt78jXZfZXZ8i9arW51Y0oT4JTPnRSy+zm6m8\/oqVni6pa\/61EeVLBvS\/wB3o2ZLc7zyaY\/nWo+Va\/2JfrvTtSG5\/wCoQf8A40Cu1tdr8xavydGsN12VkQzZ2W\/7ttKfQVWdXmc95+Rn7qQFezi1uj6+8CZ1GFa\/6105WVrkm0tzOBIROk4QEzExurlJPuzqoy8HYB08vy9aDN5mfP8AOD61avh6CBOo49Z\/FQ9hXHzHvj311HWcScQpa+Yk4wrPsGQ1IB0mqFqYWklRmDMHjmB8\/lTdPJtwuQcIJjCNejoJy46iSN0UHvJaSCBEBJAA8zPE+nlSGTekZLANlXrl1g\/PdVpt2Skneco3fJ9TwqkwYBzIy8fh+dWGWstDxA+GvVV2MWrK4o5AZkgHTXX57aMgclJTKpAJy3QBzp6+PEUKUkpg4TJynCTlAk+njXi7SslQI4gTPvqEoauOBHHfYZdnGQhpBIVoewKVGg3GPKK92hUFNH94GvbDaQWMOLNMBIAEbgr+nXqqK2ZoMzu9RUoS3f3mvCrgw05fuK7foybO8RyIQp3CcAgAEzEEdpFEU7V3i9HIWSAdDya1dnOMJq3ZSpVzOEqBCWQAnKRBAp+udj6hnL9m3\/QKm8sabrtmjS1tfSOXXPYLfa7U+ldpVZ3UobDmFMKw84oTzFACAZ131HtPs+bIpoqfceUvHKl7sMaZk7zv3U67Ot\/81vLqSx\/RQH2jt4TZzx5X\/JQeZ+7GHTX8AUVpb8f7K907MBxCXA6tJUnFlG8mt75uFxpla\/pLikgZpMwRw6Xupk2esoNlY5xBLYPmaq7VWYJszpk9BXpXmrPk92r7+nk2aYaL+gEYu61BtCk2pEFKSEqGgIBAzBqK02e1jkSpTapeSERl9YQcM80c3Wme7rGksNHi2j+kVHbEc2x6f+NZ9FVXDPVkaaXfR0\/jC02RpReA1ZQr91Q\/1Uu3g7bRb0lFkUt\/6ORycjoY5xyTpOWtdjDZ6vnupVeT\/wA7T\/6E\/wCMa0wwwVujM80nsJ6130f\/AOZDfaCT5FVVRYLyV+stXJj8LZy8Eprrq0Hr8qo2h8IwpUVSokJSASVHfEepyqbVP4xX5f7HU0\/tN\/mcXui5LRaFuoTacKUKIUVFcEkqEhIB1gnOjKPZxlKrWk9SGzPiV+6r\/s+YxP2wcHM\/53OunG3XK24MKkkjfzlDyBAo+o9TlhJpNJfdvwLDHBpNnI7VciEW1NlKipPKMoKsgqF8mTG4EYyO6uk2D2e2COc24ogwcTq\/RJFc6vK0BN4KUAYbfQACoqJ5NSU9IyTODfMT1V2a5bSXWkOgRygmCZjMjWn9RmyQUHb3W5OMY\/IXtp9lLGzY3lt2VAUlskKgkpPGVSQaz2SJH0Nwkx9er\/Dao1t0j9Bf\/u1f0mlz2YmLE5wL6gf\/AG2qMsqWKTa4rsCjckkOrjgOpAT2yZnqqHlQTm5GfA6UHvm9EWVrlVpUU4gmAAZJniRGlAxt01McksCJGIgdn2VHqrHB5cq1Riq\/v1LtRjyx8sj6eiTJnIxqPGqDh5x7T69lJjntAwqOBgEA5Eu693Jgimhl3GlK4jGkKjtAOuHrrd6aOSKqdfgQy1exQvdXPGf2eviev3UOSI+fyHrV++OkOz49VDgI6u78hWgic7vcKRORJO+Twz6tQPCle0PEqJUkGMiNBlA8ZFMN+ghGsLGgzMjt66FG6lLAKRAUUkQdAfznwpsdRW5kigGnXMCJ+Yq1Y2FOKCEDI6TGW\/w1o2rZRSUzi1GY4KgZcTnwordtzt4gtRhWuUjICe45ndTzzwSuxrJnWFMNsrJzbVBV+A9Ke6rd\/rxGztz0n0HuTma2tJD7OE6KEnsMg7vw+VDr2elVlz53KjygH1pceeMnQUHr5V9Srdmn+oUsvHmnOdPUUwXmqWz3eooDaRzNIzHqK7J9pGnD9gc7LjFzO5ZFBnLdjyro9zg8gz\/dN\/0CkBlRXczhJPNaIGm5YAEdlOlitZQ0wkAQWmzn+6KwY4uSa\/7M0Te\/4IA3Qoi9byjgxMfuUG9qBP6NP\/V\/+uiV3lw3leSm0JWoJYMFeAdDjhV6UC9pL7qgxyqEIILkBDmP\/wAuZ5qYoe3L\/KjJ8V\/ALXtP+9jXsislqzjd9GSfFRHxr3bRv9Eey\/Zq9K02RtT30VkBpBSGU84uwcpPRwcDxqDbG8CqyOjCM0KHlWR4281\/X+S0bUfwC9ztTZ2cv2Tf9Cag2gYSgWACf\/GszMcFaZVJcb6xZmCUpCeTbAOPXmJjKKobRW6RYlKgBNtaJz3AKzp\/TxayNgyW4nQAU9dJ7yh\/bqdY+gH\/ABzS5Z9qrytRU5Zvo6GgqAleZA152pmDOUa1NZL1Ui82XbctltTlmUyFNlWDGHEqElQ5k4t5jLWvR9vIlZkTR0dSk8fKqCCnlCsCTmhJ4AE4uyVT2wmpbezyLTjgJVgSpUQcyATFeXddyQhKkqJCgDqTM5yOGtZ5Ry9IdOPk577M0fpFv6nP87tP5TNc\/wBgH0sWi3qdJQlTsJJB531jskQNBI8aue0m\/mhZuRaeSpxa0yELEhCecSYOWYSM+vhU82KWTLpX0\/ZDqdRFTaFIF9wAI+k2bLtDBPjJ8a7QERoMuFcK2Vu9y12pAVjKBznFhSgQEjmwsZhWLCBBmNNKd\/aJe3IWZLCHFB1wpCYWrHhBEmZnPTPWa15PRPKoxTqlRB5FG2M+3J\/QHzEfVL9KW\/ZUkKsTkHPl1\/4bVcutbtrAILjomJlw6HeedXT9nHbTa2ZYeDDaYbxqRjWopAxFIkJSM4nM5bqOT0VY3BSu6BDLvbVUW9v2FKsoiAoONwCQM+dAHXpXMcEq5ykySRmTGWkEHTdrwortU4LPaS3yzrqkJSVLWrPGZMJ4ZYTqda6JsrbG37Gy85gCik4jhGqVFKlHKM8M1P236fGlyWU9bOUmzpkZzwkx2cK6pYCOTQBuQjcPuiN2VE37ayjGlUDLI7jIyB35k9elJFiv2DCoMqVvzw6CjHPb3RLJkSdBa9hzxluG7rP4KG8tCgNNdPyiset2JU5RjKRkNxPlNCbytPJrKoJCSCYO4xnr89VNOfgjKYs25CXAAok5656VUetvJQlBEBG7ec\/SfKnblSmSUJMDQADv6GtJW01mdccU4lBIxEwCMhp60I5lldNV+JNx2KhvVZSM+kqT2A\/lW5t6lJUQSDKkjwmPCaCqbcSmChYiRmk6EZGfnWvQ6oTEg5KTlGog+VX9mPQukPXdbyhMEyQQBrpzvfNbsW0LUgnUKkZboGnVOfdQayqUQoBJMEKmDnmJGm6TRB+WipaUkkIThABIkgDd\/FOe6keNavqMojG5bkLThGZJ4HdQ69cm9I5yfWqVivh5Q5zXglQ9Zq6m1FWSmiR2n\/TVJv5WbMaqNDqq8rL\/AGOppK0h0tQUzzscgqy7ZpgsG1V38k0FvJCktoSZSvIhIkdHjXOUPNkQpn0qXFZYzaV5f6qgorf77KuP1G7Z63WZy8LwWVoLS0M4ScgoBJConPUUL9qTNnSmz\/R8H7TFh3\/q4k6nU0EQzZScwe8H3VG\/YrMeiY7lfCmW07\/j6feDQ6ofdlhZjZrOpwt4wylJJUARmTGvZW21btnVYn8K0FXJKgBYmcPCc86SLNdFkI5xTPWSKnNw2Q9FSP5x8axvCteq3zfH\/pZKWmtvzHO51WVNnYONsK5NueeJmEzImhe3dqYLDIacQVB8EgKBgYHMznkJihtl2asxGiT\/ABVva9nmUCQierd30+NRjK7Z0otqhKu6\/HbMZbVE6giQe38qlvvac2oIDjSAUEkKSVZyIIIJOWnhUFpuQ4jBMTpw6uNQt7PKUYBV5fCvS1IxuDO3ez3ahlywMl95tLiQpBClpBhKilBMmeiE51V2h2ibTybdleTgE\/qljm5gBJw6Abu2N1BdnNlWOQQhbSSoDNW9R1JPXW177OtMplpACpHHSscsiborHFQPtTjc9HXM9Z3k1Vu++EoKk8klY3SE5RPEVpacR+wnxNBxZXpOFzB1AA+tUg2GURws+1mAQlqBOgKQJ45CvLdtElxKwpoYsJGI4SRkYz1ypSFntI0dHegVsU2sarQQdeZu379afVLyJoXgr3nakqOnhViz7Z2hlhNnZKW0JB5yRKySSSSTIEk7gKD2iyvSeYT89lU12F77hqyrom0eP2pS1FSlFSiZJUZJ7Sa7NsA1\/wArbJGRDvhyjlcVTYnZzAHaoD301Wf6tISi8VgAZIQ+R3JSFZZ7qlmScaDDa2GbwvkjCk9LoK1Gi8z2AT3ClFdsUOTJ3hRyyORJnrGX5VJfBykOYziIKtZnLORmZ99LK7WrCRJlKvI5dvCo48KaMck26Y52W8pVhPAGQftKMxIOoBHZGcVtf1sxjI5FMnM\/ZkiO2BpxjfNKt33jEpJMAgmN8CB2+PGp37aSmEkxnHcOdl16UzxVIDR3YbFsTOMzxkdXV1eZrZOyFnE89efAjwAjSke3X7adz6+4x6VVTeFoUM33NPvq+Nav8GHNHf5cfA\/r2Osp1KjlGfz10pv7HFS18m4iMRgEkZTkNDQN5bpGbiz2qPxq3Z7WU6FQjKQeqqR9JFHR9UuiZ7ZO0I0bKutJSffPlVJ2wOo6bK09ZSoe6KKsXy6NHCe2D60Ss207w1g+P50X6NdMovV\/QUg6Pu+FStuIBzT6U5f28yv9aylXahKq8wXe5q2E9ilJ8shU5ejkVj6tC028wdZHaPganS3ZlfbA7ZHqKOL2Zsi80OrT3pUPSfOqto2HUR9XaGz+8kp9Cr0qL9LJeSy9VFg3+y2T0VJPYUmtVXIKmd2LtidAlf7qx\/miqbtzWpvVpwdYSSPFOVI8Ml2UWaPg3NxHd6mtTcauvyquLY8gxyiweBPuNWWr7fG8HtSPcBU3DJ00OskPBAu5l8D4fnUCruWOI7iKMNbRr3oQfEe81ZRtAhXSaI\/dUD6gUKyLlHaoC\/yDo3n+Y16lTydCfGmNVua+6T2x7iahctrcfqge\/wDKh7g+gGN3va09FxQ\/hSfcahvC\/wC2EgFZiP8Ay065\/hog4+k6NJ8T7qquKJ+ykePvVXWn0doAy7ZaT9on+FPwqBTto+8od4HpR6VHopT3Jn1rYWO0K6KF9zfvCaZMWUULK\/pJ\/aLP8Z+NRmzWk7nD\/ET76bhcFsVoh3xI9SK9\/wCCbYvVJ\/iWPiaqlJ9EZafIjWmzuJBK0KgakgxVFxYrptn9nVoxAqLUZ5YidxH3eNePez59OSUskb4UZHZKaapLohOdcHLQZqZhtYKVAb8u3up9c2KtYy+jpw8EqQZ7edNVf+H7W3P6MoDPRsmd\/wBnjFI5S8EXkfgX7ZBSSRHOx5ZZanXLq+YpbUkIJiTiKhwAGRHGZBBpvvizO4FS04DxKFCeOo7KVrU0oQkpzUMuOIJCdAdZp8L23ERrZwRnEzrAHZHVV5sSIJynL+IRx8uNaM2b6pUoIyjtM849o07q2Q4QokiAoToRzpkgeIMCme4WPTgkVqDFZWV66R5aNkKmKmwCKysoSVDGzLIw+FbJT11lZQ7AWEp5teEVlZTRKx4JAwIBkieFeotbiNHFd5msrKZDsns+0jqeB8vSjNi2jWqMvOfUVlZQlFHJsNKtuIQtCVDrFQJuqyu62dA\/d5v9MVlZWecI1wWhkldWRP7GWY9HlEdip\/qBoZbdiQgYkvZDcpEnxCh6VlZWaUI0aozdhprYRkCS4s9yR7qmRslZhqFH+Ij0rKyp+1BdFfdm+ydGzlmH7IHtk+pqw3dTCdGUD+EVlZTKMVwhHOT5ZYQ0kaJA7AK3w1lZRFPMNZgFZWUTjwpFYU15WVxx4qtcNZWVxxqpkVAuwNq1SD2gGsrKIpSf2csq+lZ2j2tp+FVHNkLGc+RAP4SocOB6h4VlZXaV4Af\/2Q==",
        "status": ""
    },
    {
        "property_id": 10002,
        "title": "Downtown Studio Loft - All Utilities Included",
        "property_type": "Apartment",
        "monthly_rent": 1450,
        "bedrooms": 3,
        "bathrooms": 2,
        "city": "Springfield",
        "description": "Modern 3-bedroom 2-bathroom apartment in the heart of the city, featuring an open-concept layout and a balcony overlooking the park",
        "image_url": "https:\/\/images.pexels.com\/photos\/439391\/pexels-photo-439391.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500",
        "status": ""
    },
    {
        "property_id": 10003,
        "title": "Modern 3 Bed Townhouse with Garage",
        "property_type": "Townhouse",
        "monthly_rent": 2850,
        "bedrooms": 3,
        "bathrooms": 2,
        "city": "Capital City",
        "status": "",
        "description": "Newly built 3-bedrooms 2-bathrooms townhouse combining comfort and convenience, with private parking and shared green spaces",
        "image_url": "https:\/\/media.gettyimages.com\/id\/108220043\/photo\/row-of-suburban-townhouses-on-summer-day.jpg?s=612x612&w=gi&k=20&c=kWvOt3XxQfHJ9bO4WFOpsj5vOW7dj_GMijXzb3TbAUs="
    },
    {
        "property_id": 10004,
        "title": "Cozy 1BR Near University Campus",
        "property_type": "Apartment",
        "monthly_rent": 1200,
        "bedrooms": 1,
        "bathrooms": 1,
        "status": "",
        "city": "Riverton",
        "description": "Stylish studio apartment ideal for professionals, with easy access to shopping centers, cafes, and public transport.",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcRw_5MejScwltUiRNpwKr2nAO6kpra1EsdaGw&s"
    },
    {
        "property_id": 10005,
        "title": "Luxury Penthouse Suite - City Views",
        "property_type": "Condo",
        "monthly_rent": 4500,
        "status": "",
        "bedrooms": 2,
        "bathrooms": 2,
        "city": "Capital City",
        "description": "Luxury corner condo with upgraded interiors, hardwood floors, and underground parking.",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcRKdwFOJIHUC70NPoqqf_Y3WnmgmuQnKF5n2A&s"
    },
    {
        "property_id": 10006,
        "title": "New Build 4-Bedroom Family Home",
        "property_type": "House",
        "monthly_rent": 3200,
        "status": "",
        "bedrooms": 4,
        "bathrooms": 3,
        "city": "Oakwood",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcQPwDP0CRj6mGjl-WU6r3k29z0boDMGxAySWA&s",
        "description": "A beautifully designed 4-bedroom 3-bathroom family home featuring open-plan living, a modern kitchen, and a private backyard perfect for weekend BBQs."
    },
    {
        "property_id": 10007,
        "title": "Affordable Basement Apartment",
        "property_type": "Apartment",
        "monthly_rent": 900,
        "bedrooms": 1,
        "bathrooms": 1,
        "status": "",
        "city": "Riverton",
        "description": "Elegant 1-bedroom 1-bathroom apartment offering premium finishes, a fully equipped kitchen, and access to gym and swimming facilities.",
        "image_url": "data:image\/jpeg;base64,\/9j\/4AAQSkZJRgABAQAAAQABAAD\/2wCEAAkGBxMTEhUTExMWFhUWGBcaGBgYFxcYFxYWGBUXFhgYGBgYHSggGholHRUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGi0mHyUtLS0tLS0tLS0tLS0tLS0tLS0tKy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf\/AABEIAMIBAwMBIgACEQEDEQH\/xAAbAAACAgMBAAAAAAAAAAAAAAAEBgMFAAECB\/\/EAEAQAAIBAgQDBQYDBgUEAwEAAAECEQADBBIhMQVBUQYiYXGBEzKRobHBQtHwBxQjUmLhcoKSsvEzQ1PCFWOiJP\/EABoBAAMBAQEBAAAAAAAAAAAAAAABAgMEBQb\/xAArEQACAgEDAwIHAAMBAAAAAAAAAQIRAxIhMQRBURNxIjJhgZGx8EJSYjP\/2gAMAwEAAhEDEQA\/APSwxrZedGE1AMQRWHEzvWRpRhwhGts+ldW8VBhxlPyrS3qka4GEMJpAHKisNKFfQxQnsnTW03+U1wOIZjDDK3Q07FQZnrlr0VuwmbaK7uYE+FAbEa4gdalF2q18IORiufYsNmpDotg9bz1Ue0uDxrYxjDcUCotWugCt5+7MVVfvw50QvEF9k39Onx2Hr9qaoKJhiAdqhu4gCqbC4yBHOi7VhnPh15Uh0ZcxBNEYfBk6tp9f7UVh8Kq+J6\/l0qdiBv8A3qlHyKwX92jbauhW3uz4CuZqWMkBreaopreagVHZah7QztJ91dvE\/wBq4vuWOQep6CiUgAAbCgCUmtTUZatZqYiTNWs1Rlq5L0DJs1cPcA3qt4jxe3aHeMtyUb\/2HnSdxPjty8YG3Qe6P8R\/Ef1pQAxcV7TKsi3B6ufdHl1\/W9KWIxt282hOu7tqfRf15VwtjUFjmbl0HkKIVeQ9T9hQBEtq2NMub+ogMSeck71lGKgrKdBQ7XBQd5yKscSh5CaBuYO5vHzFZlkKYyi7eIoE4c8xFTWsKeVLUFFnZvDn8jWryq+jCfHnUFtCNwR4iirdufGnqQqAxbe2ZQ5h0PvCp04qTpPoRBo1sL3ZB9KAxWHV9xr151Vhyde1reeq8o6f1D5\/3ru1i1ai0FB01yRUIeujcpiNtbHSuVNhlyAgseisROuzRHXnXCqSRmcgTrlgE+Gsx5jWiL1\/CiEa6VJ1AOKuhjruJuSRNNKwbB8LwkAyxnw5evWrRF9BVcbwtN\/1C6Hk8EqeUMAJG+jSfHlUGLv3HIgwvQbevWnshclu98D3dfGhi071Aj6a1meobsdE+aszVBmrM1AE+ao79\/KPHl4muC9RYc5jnO34fuaACsOmUa+8d\/y8qlzVDnoxcL3MxPKaAIC1clqjZ6qOI8cVDkTv3DpAOgPiftvQBbX8QqgliABuSdKV+LdqPw2Z847x8hyHiaBsW7+MfvkqoO0QQdiAp90+evlTVgeylpB1PPqfM0Wh0InsXcy868pOvmedEC1lG1OOJ4Wg2EUt489\/ImrfIeNNOxVQGAdhvzPQfnU6W4qZLAURueZ61o0AcxWV1NZTsD0S9Zg\/r9c6jcaGj7iyKDakiQMiurelDG62aAAV6zBHpGvyqYNQ0NMsbBU8qy\/gwdRQVu7FH4fFaaxUaEO2UuJu3E5yPGoFxpO4pjv2rbjvAH6\/EUv4nDhXIG3L4Uqod2a\/eKhv2lffQ9Roa26VA0inYEZa4n9a9RuPMVLZxobY1EbtQ3rStrseo3\/vSsZYi9WnCt7yg+YB+tVaNcBgjMOo39RRQtMeR+Bp2Kia\/aDCosxVdJOuwMnzjpXJtnn9RWijDXK0dYI+tUnYBOHvyKmD1XrbjY\/MfnUqseo+I\/OpsAzPWvaUPmP6IqG\/dOwEk6D+\/hRYE7PnbINh7x+3rRoNB4a3lWOfM9TzNS56dgEg10zmN6GW7UoOknlRYhP4rxi7duNZt90KxUnmYMb\/AGHxqXD8MVO\/zgT\/AE+I6DrQPC0\/iZz+MmfOedMV5tKV3sVR1axIYgyFujnstwDYNGzdG+u1S\/8Az51B7pG4O486X7zR5fT+30oXE4jNGYSRoDzI6HrUKLT24\/RTaa35LXiPHWbRdzt+dB4Y5R1Y7mhLSczv+tKmBrajMIZ5rmajFctc5DU0wJs9ZUX7ueba+n3rKYrPTf3ryoU9+QzGPCBXnl3sdj1UFL1s+V68n\/oay3wLiyjS4D5Yhj\/vSuf1H4NvSj\/sehW+GgyQ5HmJ\/KtPw1xsynzkfnSIcJxpNu9G8Nhz9SDWDiPGkBJw8jxCH\/ZdqvV+gvSflDigaYJA8dY+QrvEOFE5wT0AP1pGPajiCjv4JiecW7v2DVi9tr348DdHo4+TIKr1BemxzHEPH51KrZhmpKXt\/Y2uWbq+YQ\/+1XfAe0tjFMVssSQJIIjSY3250tSYtLRa3FoZ1oy4KgYUgI7VgE61H2lwXsrHtLZggrI3EExsfOibA7wNS9ob6\/uzlyAogknYAMCSfCoYxKucbuEKrR3ZjugHXqRvXWGx5adQsAnUxMch4+FB3MVYcg27ttv8LqfoaNt2ZqbYzq3xLxqzshup8pMfDagFwuo8x9aN\/wDk8Ophr9kEbg3EEfOmmxMsMPbk6k0ULA6n40LwrF2rsm1cS4BoSjKwB6EqdDVllqySEWvE\/GpEwhbmalVaOw1DAHtcNIB0B05zp40BiMGRTF7TSgcXrSYFLhbepmpeItlsXD\/SR6nQfWhOI425bYJasNeYiTDKgUTAzFjz1+BqtvcVuXcJd9rbFtheNqFbMO5BJmNe8CPSqQAOAQZYOx38D+tan9udVO4+Y5Gh7bR9\/KtYgncbj5+FJp8opPsyHFXY1\/XlUFq3zO\/IdB0rVoScx9B0\/vU9aJprYhrcwCugK5mtKM3gvXr5VQG5J0Hx6VIgA93fmaxegED6+dSKtMDn2daqWK3QAnY7GY+3fu2kv4khLrJPtLjAd45CdTuoB9ambjWPTT94vEbyZmJiII+Y60x9sLd7D403bLFReVC2gIzAezOjc9B8aW7mNZL6teXMqz3VhSyseZGm55afWvSjhhkxptcrlHMuo0ZK8PvwbbtLxBBnOIuhSYEhNxy2P6itXO2XENjfaYB9y0QZ007nlQyszF\/ZoVDaAXmZu7m1zRlHLfKILctap\/ZstzKqliGOZG0A6AgQQJJ5zG0b1wT6Wn8N+x2LqoP4aV+Rx4d2sxlwwbmukdxNdNR7vr61dLxnFBJZhOkdwaz5RpSfxLFGw6G0BbGTOyE5wGCaLG8E7a7xrTz2Zxdm\/aRjIuEd5HBUiecTsYJB10PhXi9Zj6jHJOPHuejgz4JQ3Sb9hdx3a3GWyQfZkAT7nqfxcl19KuuzeN9tfwt8gBrtjEI0CATbuodv8p+NDdsrWFUGWQ3FGYoRup0kba77bA+Vc9lkKDB93KExF63EzC3LDMNR\/VFdHTSlOFyMupcP8VQ\/FahZaJIqJhXWcBCBVZ2tRnwWIRRLNacKBuWKkAD1irU1WYk5w4YSpGx2Yb7dKlruUeFYdJzAgaKhGmoMa1E\/Eb6nu37o8rj\/AJ1a4mwEBIEEyvwkD6VQYjc1vGmZvYbOwfFr74y2j3nZTMhmJB6b1e9hcPauNjA9tHIxNwyyK2hmBJHUGlX9n1stjbccjPwI+00ZgOLXMP7ZrRAL4u6GkTICyB4asaznG7SHF1yerdmcOiviAiKii4uiqFE+xtE6DnrTAEpU\/ZjiXvWb114zPfOwgaWbCD\/bTnlqdNbDsXX4U97FsBeu20FpWOQkZu8yiDMT6HQRVtdwIt5FN92USdcsgAAk5lAJIEmDP52mDRRJ0zRBPMLmJGnLUt8PCl7t\/iHtWrZs25ZrqqWBAKAhmJI3YSq6bczIBBpQXIOV7FPxvHXrWDv3MzB1tOwafxLbASBsCTBPjPXSv4VZe67ZcTiEcAEh7juuoGaLeYBQCSIJPKAIojtdxC5awhYDK5e2Iicxe8uZQN9iRG+oilHhPFiTfZUb+HYvtMghbiZCLZI36GYmdqhxtCUty8x2e4mFl2D3ERiZ1ksSoJBj\/vgc9BRoQDC2RP8A1bt+76XLzOvyYCuuIMllkT\/wW1jp3LLH5DDk\/CiMXgwq2LP\/AIsOq+sKPt8qce5b7AhskVA5j0+lF22lfEaHzoa\/O9XRNgd7unNyO\/h41Ln0rRbSImeVQ2+7HOPkOgpKLT+g27ROF5t6L+dShZ3rLS896nWrIMVa6ititigdmorVdTWUBYz8YwiObFwicjETyh438O786QO32AKC2oQgKrE6aEzqZO+1PHC8V7XAo5EkIpYc5tkZhpzhT8aiu8WQW8oi7IgA5ZAP4TJ8h611dJlkop1dbf35MM0Kk19\/78HmbYPFYxMzOiarAgh2SZJlRIboPDlpXGBtBG9pdvXMrvctqSgbOZjOx\/FJy97bXxmuuI8ew72mH8RF9oT76gDMe8QgClyFO0kanXrbX7N28tgJaZss+yuYdTCAhdbgujKBuMp13gGtZ2nq4JUO3Yg4pjgt+4vdUqU77QyKSmYZsoJVx020JgwAaY4m+l32ivbZyiEMyspCtCzbHMSWExBC0zL2OxytcFtrKW7in2jXBBZmXvMLaSuktBJB11nStY3CYG3byY3HpeKwP4ajMoXTKCucgRvMbk6EmuPIsUlU9zoxqcPlF2\/Yl2ZmF5kyMwbRWBbvbQ0AEHefLQVf9msaGtBwICYzDmIgKHcW4C\/hXKQI+u9S4LGWXCjBcLu38oAW5dhUgbHO5Kt8Z2q6tcE4hfyi\/csWLQdG9laTOTkYMAWaMuoGxO1ZuqqK2Nbd3J7jgRUTCpmqNzRRlYO9VF59LgHvKG5baHL5\/wB6tb7gUu4zj9rvC1Fw6gkGEB2IzaydxoDqNYqWhpnkNy8WtK7RLGTHjJ+tU906mmTimGy4bDoAqkBg3vE5kJBkzl1nl0pfGGYzAJ8gTv5eRq4cClyNH7MLx\/fFT8JGb1XQR\/rPyo3s\/wAFbELdZSoyYq8xzqWn3do20FV37NRGOUkGMrD1LLA+Rqbs12oGG9qrKSrXLjEgayWEc42U\/HwolfYVpLc9a7A4Y27NwEKD7Vz3dBso00HSmelj9nGL9thPakEZ3cwdDo5X\/wBaaoqaHYkYjiLrjXIuXBDqoUZshTQPmVR3iMzFRMST4A74nnuXbbC67BMQWVWBHsrRGUvkMHMFZ0B1ABJiSMt92k4ibNqVtvccsoCoJPeMZtdIG5nSKCw6B7Cm4t7M2pBjOhmIkaHTpNZOThdGySnVi525xhW1atoJz3csCO+gtXGYgj3dhry8qqux3Dlt9xwoLOqicpLqHB1AmQucDXr0p54nbtjD91ASAAAR3gBvqeZAI350ucKxmHuXw9sMDZFzMGUrDW0E5Z+NZ5U8kdPt+xQahOwbiLm7ibg3U5EA6+3upaJ8YW61FY\/jdtsRd1iGyCdJyEqY9aj4ZhXXFIHWP4wJkf8Abs2bzAg8wWWydKV8ScxLHmSfiZroiQy+OPAMzodD9j9R6CubuPWJkUoY68URiDy25dftVVhuMy0tPhzA8oq4olsfBeLeA+fr+VEWnDA\/zD50s4PieYaGfI6\/DejbONghhuPpTAvrDx5H5UaBVW+JXKHBGU\/I1uxjiwyopY8jyHnUyko8lRi3wWT3QokmgrmPJMICT5H6VlvhpYzcYnwG3x\/tR6qqCBAHh9+vrUfHLjb9lfDHncrv3a6dS0eo+wNZRv74vLXyBNbo9H\/p\/kPU+iLPsLeAXEWX2S4W1\/kuid+kzVU3CuEYURdxT32Ghm6WPkRZA+dYf2dXrzZr99bawBltguxC7SzQAfQx41eYD9nmBtwXtteYc7rT\/wDlYX5UYHlhCk68lZfSlJvkV7XbDBWmyYHh5d+WW2Ax137oZz60cMRxzEnuWreFQx3ngN8DnafQU\/4XCW7a5baKijkqhR8BUpq9F8sz1+EINv8AZ490zjcdevazkXup5SxY\/DLV9w3slgsPBt4dMw\/E03H\/ANTyR6VesaiZ6pRSJcmzZrTNQ17FAVQ8X7R2rWjuAegkn4DWmSXl\/FAUu8Z7WWbBCM0udkXVj+XrSlxvtY91SMPKrsbn4o\/pXl5n4c6T8TYBXTQgyG3ObeSeZJoIc1whj4z2nvXn0Yoo2RSYP+L+b108KR8Tg8sZJPUdNKt\/b5kz7HZh0YfnvQN+2bhkafenVERm+4EEuCe6fHTxj7iireIfIENkEA6uLff15ZiCOe0cq6GGYc6u+A3WS4gOsso311YR9qT4LU9y47IGxh0V3m3mOf8AiEZmACkHQe7rppzqo7NcLFwPmfK8t3dNiSQRTzhOFLet5XUFcuTxVkZ1keOprML2PsKcxNwkbEvEc9IisNXJvKCkqYyfs6shMEq66PeGup0xF0D5RTQDS9wVfY28ikkSx11PeZmPzY1ZDGkb1omiWgu5bB3AqiucPU3rrlQQuUgQOSa\/rwqz\/f8Aw+X96X8XxqPaDKe8Tz5bfSsss4xpsvHFsyzYL90s0+zEwzDVmOuhnQCuey9otmZyzDUQzMwgwCIJg86CtcfQMZDAQw5TlAEDf9a1bdk2BteM6+Z1NZQabVGk00nYDj+CnD+0urdPsUsXlt2jr7N7vsx3G3yd2Ap2kxoYCHdr0ft1iQmEafxMi\/8A6n7V5Zexq11IwAOOn+GaVxV\/xe9mSB1qja0Ryq0JmJeYAgEgHccqt+Ay4uD2qowUZQ349SSF6EQDpyqlip8CsuPCT9vqRTatbBFpPdDb2eUPcC3SzKfd1hc2+36507CzGm0bRoB5AbUo8Fw4I1\/4PIimzhmJ9opB99DlbzEH4EEEedLSkGpsnALbHKeenzFbt4ZNzLnqdf7fAVhUjUbj5+FS245bH5HpQBJnPStVk1lMB9rKysNIRyajd61degMZxBLYJZgAOpimAS71R8Z4\/Zs6M0udkUS39vWKXuN9tt1sCf6jMeg3PyrzfiOOue29qWJMyfMnXT41SRlLJ2Q38X7V3rkhP4S\/Fo89h6fGlXEXM0kkknmTJ9Zq1vgPbDjmKqSupocaMFkcuQfA4j2dz+k7ii+KKF1GxFVuJPeEan9b+FEtJChtQNtvnR2oprewe0kyTsY06xME0StupFTSRrUhXQHlMbbHxooHIhGn69atOzOCa9fQDQKwYnoFM\/OI9apxnuuEUSzECBzP26+VPHC8VawSC2HR7xIzgBmKTqc5WcoA6xsaiT2pGsIW7Yxcb4muDs+0yFiWCqi6ZnMmJA02OsGkR\/2h4vMwFq0ImVyuxUzGrZhPoB51Pxnt5au28j4a4BmBVg4Ui4kaiVOozeO46zSlh2wpaL\/trexUoLbgAiQXkS2kbTzrNR23R0Nnp\/Zztl7Q27d5ArvlErOUMwEKVMkamN+no6RpXmvZSxgrbLeF975XZshCBuuU97MJG\/PWnvD8Ysts2\/gfyqNuxRY5aFvYJDqUUnxAokmuS1JqxplXc4NZOvs19AB9KtOF2AiwJ1M6kk8hufKob18ASdudR2OIr1Hx5UlFJ2EnaorP2goHtpaJiczeREQT4amvKsTZKkgjUV6R2nxme8AsHKg57Ekmlvi3DTcXMNXHzHTz6VsZidfFCutH3hQlwUIAN7dTcPsGSQCYidNhOvzK\/EVy1MPZHilqznW4D\/FyKWiQtv2i+0nnqoI0q0Sy54OkKDR+BYriHI5ohI9XH0UfCrbjfDLNpLd6y38G8RkA7y52BbRhspAMTpOk6gUBgFl2bxAHkAPuWqrsC50IkVEdNeR3\/OssmNOXL8qzFGATSGR3BcnSCPE1lSYS13BJMx9dYrKAPRK4utAJNdzVfxm0Xs3EG7I4HmVIFBLYnce7dWkJVMzD+YL3fiSJpQ4liTiA15LrOq6lHhWUcyAO7FDsupRh1BB3FVFmEZgL2SCQCNHHUAxp570Si+UzCOVPaSCblwDz6c6pcXbuMdLbR5Ve2jb2Dr\/qH3olMLOoYfEGqbMk0in4XxF7aFHtv4d0\/lQF\/FMxOhUeMzTZ+7EcvWtmyOYp7salFO6FawVGx89NTRmG720jw01q0xVtAPdB+FBYLDe9cKCJ7vl9qmmVqTRIE0JiCBO\/1oLF3jJjmAfUAn60ZjboIzAGcvM6id1Pyo3s3wxLjvevELatGSW0UnkD5AfSqewQjqZT5vYQDnDuFL5YDC0W1VSSMrECfhqKDxODtrd7lwG0XYKwILqmbQuhgq0ETIAJBg0w9p7ts3FxQtEJdSLQfum5lzKb0KwZU1XLPvR0pZcq5dmAEgkATGYsBpr0JOvSsjr44PQeO8Rwx4cmGyWmaywKywzMotlixTNnBckbE+J0ICDZtpdbuILbHQJbdgpIk\/8AczkyQNM2\/wAp+HcEuX7V17Tg+wXN7LNDlJzE21\/EBqYnrzOoZxRuf9ViT\/MQGYn+o+8R4z8aBt+Cbh15rLhx7ykwIyxyMxv5GR4V6VwrFZ0W4BowBj+WQDERSDw7g7XbgtKXzRM5AUCmSpLhuYHTcRoZh64UckWx7ohQOkaa9TUT3ZUT0ACtkV0BWEUqAr+IjuHxqgTTTpp+XyimHianIaob42bqI+4+\/wAqaRMhe4kf4hPjUti7XOKEz40AmKC6E1oiQftHwyQbqD\/GB\/uH3pNvswMT\/evTcNdBFKXaPg2VpX3GOnLKeknlRQxWa43X6Vd8GshnCsAREajfT\/mqf2UMAfXy50ycGwD+0GohSTmEmeQ9DM1cSWO+M4mz4cWrvfRdFH4mJgKs8z0PKpeFYfIiqdTGp6nc\/OaGweDAOYyzcieXkBoPPerNRSoo6ZZri6hcAdGGbyqSajwQlmb0HpvQAblrKu8NhVCjMNY1rKALsml7tdxsYa0DEsxhRy8SfAafEVes1Jf7TMCLmGz5srWyMp\/xMFj6H0oRnK6PMsfxdmuFmJLMZ2HwAjaq27YLsSOfXepGPswGI3jXz5eFbtYxi0BeXkfSq9zm37EX7meorZwjDlPz+QrFuiTOhPyqW3itdZ5H0o2B2aS045MPKftR2HxFxY77jbQnT4GoGxJOsx0H3qYY2VjoZnp5VSoh2WPEkXOATlQlRJ\/ExG3z1ofEIbLMZ7uWIG2uxA66fKikxS3VjLoI3Hhp+vGglUt3YnvDLuSdNvj9aT2HBXsa4Vws4i8bQkAkZmAmFAq27dILZs4QZlsW09o5A94lmVZ8SVIHi5MaU8dmODLh7eVvffvN59Kp\/wBoPZ44gI6NGRW03B5qYkRqSC3IN4VjKSXJ3Qx0vqeU4zENcdrjasxk\/CBv0AA9K6suUUnKO+CoJEkARmKztOqz\/i2o09m8XqRh3IHMAQfIzBo7F9lsU9zKLbC2kItwjKpRTAbvRJI1jx60tcUrvYrS7qgDs5dIu90gNBa3Kg\/xlBNvcHqRrpBPhVth+zuGQ5GvPdI0CWxkU9Aza\/KTTN2Z7GpaJaRcYyJaRlEawBr8x+bNwrs7asNnIzvMzsAddQDMxMbmK55TnOVQ48\/25vGEYq5c+DjhHBrdhcqW1VQJnnPOTuT40u4YHOCpMZhrrr4CeVOmMuAI8dG5dBSxw7DGZkaEQecQNK1RDHvLXLrRBWubinpVURYDiLcgilfGuqAoZmYEAnc6bfrSm+4tLnaDDaZhuNR57inF0we6FnF2yAZ0IJBHQjemrguDt3sHY9pZR1KBoYAkEktpI0Ou80s8Qxi3s7qCDHeB5PGsdQd\/U16hw\/hSJYtW49y2iyN9FAmD5VVEnnPFOEC1dRLFsgPnJUv\/AChT3c7a7nQE\/I1zjOEObfeRoMDLBza7EDz25zRX7UbiWbmBLA3AL5cj3e4gAIzbgy6x5H1jx3Eb2Iw6XTbuBbdwtmUyCoIgGQZy8ztpy3A2kt3Rrigpy0nmnF+Hut32ZBZ2bKu5ZmJyxG+adI605YHDwqkqVYDI6kQQyCNRyMRTdgcXh3Fu8h9owDMXHJvxEye6ddR48xS5jLpN+4Ha2Wud9VRR3QNszwCXI38\/IUoT7NUx5sKx8sMsCiRQuFuggUYtaGJHiXhT1o7hGFkqvTU+mp+f1oBhmcDkup+1MvZ\/DTr\/ADH5Df8AXhSAu7OBJAMxNaqyrVTYiqJoHi2ES7ba3c1Vh67yCPEEA1NdvgaDehXuTRY6ELjPYtQhyOxiTBAJ68vWqHFdh8Suqsh8yVb4RHLrXqxWuVsCo1O+QeKNHjN7szi11Nhj\/hKt9DQnsbloHMjrprmRh8ZivW+Ocdw+G0dsz\/8AjWC\/hPJR4mPWkw8SxfEHKW0K2phgphAP\/sf8R8B8KfqUC6bUrukJlu5M6xpp511bAAnn1+3zr1mz2ZsC2qPatuVUAsUE\/HeKT+0PA8MjFLYKsNWOdj\/lAaRPOqU0ZvC+wDwh\/wCGQJBDSRzbQj6n5U5di+CA\/wAZxBB7oI25Tr60J2X4DYgXAzFwIysVgNtn0XzIn7TTxhsOFQLEADTyqHlUm0uxWPBp3YQE73WflWXsMuo1O49akwyxJ8oNTIQBm561Lpqmb8C7wziwsI9k5gyzkKjUzJA0qpx+Iu3MuYkkaZpMkHmfLXWrDjWB7+YaGfvpQLH3Rzn4TpHyrw29Mq8HoRSasvOzuaSrLEQfOeYPSrm64GhgUBwi2QhPhA\/X63rvETofl\/avZwyvGjhyL4mVvE8QQrDcR9TFVinY8p2jarDiakLBiDHpqP1NCWMubKATvPwqkSx6y1y9S1G1amYNcqv4jazKasnFC310NIZ5xgcGRi2X8OUkjwkQfSSPU0528Vete47QOXvr\/ofVR4KRQVvCgXnfnly+hM0JdxL22KzIG09OWtaRZnJC527xt3E3ldwsW1yjKGEgmWJDExr\/AMmr7sVjr1zDNYthS1ogqWJAKOWMEgGDIOtVHGDmlj1qx7AXGtM7xKMQpHIxr6b\/AFrn6uDnjqKV8qzTDLRKyjHC7tnEMqsLSuwBRy2QFpXNIB7h3kbAeFT27TLjxbxC+zgRcIkqykQlwNsYYgg8suuxFN3E7T38XYLKlu1bJze0IYP+LXUc8uUa6zOlWPHXQWHQ4hbIvEotz8IYakSug1VgZI586cNUoqTVSrf3OzNLFkqa5S3X3E9sObGQHOVYe8Vhc0nQGIHgN6PtvpQeG7HtfyXGxmYEQ7WnLAi2xCqFIhhIEERHSpsRYtoxs2rrPEAlhqG1kbCRGs\/WtIT1Lc5pR7pbBHD0La83OnlsKfuD4cKvkIH3pW4Rh+9PJRp57CnXD28qgfHzq2ZPk7rKysqRCmDNSBaixF9Lal3ZUUbljApJ49+0ACUww8PaMNf8qn\/2+FQ5Gqg5cDbxbilnDrmuuFnYbs3+FRqaQ+LdssRiH9jhVZQ2gy63W9R7g8tv5hUfCeyGJxb+2xLNaVtSW1uuPAN7o\/xbfy1nF+N28L\/\/AD8OhTIDXQFdnaYgFwcw8dukVHJolGP1YdwTsKdHxTSSZ9mp5\/1vzPl8TTtYsqihEUKo2CgADyApS4FiuLs38a1aFtfea4uRj\/hyNr5xHjVxiOK3U19mjDmAxkeu1YTz4oPS3uPTOe5Lx7iq2FAHvttzgfzEUiiyC2Yt3m\/mAmSwM67H8zXWNxD3LuYwXJB193lp3tAo21rWFstMiCR5nxJ06VsnatGY7cF4eLahVII5mdzVuLhEjl5\/SkfA4xpgzpBkaDnp86OXiTAyHO+x1HXn60qKsdbbEr9amtEEDTX8tKWRxS4ApldRuQPLUfarD\/5IbOIJAO\/USKdCsLx2D9oZUw3yPn0qutcFhu+4jX3Zk+pGnOiE4qh6g\/rpWjjQ5kN4a6fXWud9PBy1NGqyySpMPuXFVco0Aih2u9eVDX2JHXxH1rhmIgVvZnQJxm+IGYEDOAdNt9aAw9wM2w8OX\/MVLxq7OVTsc2\/gP0KG4dc1AAAj899edUiWelDauWFdTpUTCtDMiuGhbtEXB40Ncmk2NIpsWMtweOn5UBxe1IDeh+368asuLqYnmNfhQph0jqKcWEkLmOtSh8qv+y2Ey2V6kSfXX7\/KqzJOhq04LigsWm0I0U\/zLyg9fD1qnuQhmw623TIwB30aDpr13EVXYDhGEa3csIEdFuszrvFwjedwQDEjpHWl3ttjSLLKsFn7vmPxeO0ilLhNwp7pK+IJH0rhn0GpuUZtWaLLW1HqXD+DJh0OQE93VcxAYqNDrIVj1H2EUd7Gi9iHjKUt6I2UZtRJGbeBrp4iq5O0F9LRTPKwdTqR5NvRnBLQJECMxzN8BP5Vt0uHLjTWR34KlltbDbwHC7f6j9h9KYDQfDbUJPNtfTl+vGi66mYmVlZWVIHgH7Qb7HFXFLMQuXKCTCyNYHKaJ\/ZVYRsTcLKpKoCpIBKmYlZ2MdKysrHudcvk+w3ftAuMuEMEiXQGDEqTqD1B6V5LwlA+MsKwDK19AwIkMpcAgg7iOVZWU5EL5T3wKMi6cyPTMwjyjSq3ipm2J\/W9ZWV8zl5OuAg8cEMsaa0Lg2IZY5kT4+darK9vpP8AxRz5fnLlfcby+4qewP4XqKysrdGZd30EWxA90cqixY2\/XKt1lakHKa3FnWWE+OpqG4dT5n\/dWVlSykbQwTGn\/NWmDcnck6DfXlWVlIYHx8d5PX7VV2vfH+WsrKFyJnqHL0rlqysrUzB7lDPWVlSykAcS92qnB+6PX6msrKceQkC3f+o3n9hWsYoK6isrKszFzFb0Hht\/U\/U1lZVLgnuHX\/dHmPrTf2c95vIfWt1lWuR9h\/WuqysrNjMrKysoA\/\/Z"
    },
    {
        "property_id": 10008,
        "title": "Furnished Executive Condo - Short Term",
        "property_type": "Condo",
        "monthly_rent": 2600,
        "bedrooms": 1,
        "bathrooms": 1,
        "status": "",
        "description": "Modern condo unit with resort-style amenities including pool, gym, and community lounge.",
        "city": "Springfield",
        "image_url": "data:image\/jpeg;base64,\/9j\/4AAQSkZJRgABAQAAAQABAAD\/2wCEAAkGBxITEhUSExMWFhUWFxgaFxcXGBoYGBcXFxUXFhcYFxgaHiggGBolGxcVIjEiJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGi0lHR0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf\/AABEIAKwBJQMBIgACEQEDEQH\/xAAbAAABBQEBAAAAAAAAAAAAAAAFAQIDBAYAB\/\/EAEoQAAECAwQGBgUJBQgCAwEAAAECEQADIQQSMUEFUWFxgZEGEyKhscEyUtHh8CMzQmJygpKy0hQkU6LxFRY0Q3OTs8I1g6PD4iX\/xAAYAQEBAQEBAAAAAAAAAAAAAAAAAQIDBP\/EACARAQEBAQADAAIDAQAAAAAAAAABEQISITFBUQMTYSL\/2gAMAwEAAhEDEQA\/ANLCx0dBksLCQsAsLCCFgOELCCFgMp0i\/wAXK3S\/+RUaoRlekf8Ai5O6X\/yKjUiKn5Pjoa8LAOjoSOiKdHQkdAZ+1H\/+hK+z\/wBVRpIzVr\/8hK+z\/wBVRpRBAHpl80n7UH0YDcIz\/TL5pP2vZB9GA3QDo546EiK54jXZ0K9JCTvAiSOEB5l0jUtRXLvG4COzwBbWRXAxll2cp9FSk7iRGr02D1qwNY\/KmBM6RTCMV0ntFodClImFalKYEBz9UxppSIDaGQAiYCQCXYEgP2cnxjSy0I9YHd7Y49a7TIrJT2\/ujxMWky4iXPQleOIADAqr2jlsB5Qv7Yg0CgTqevKM3lZ0luiGlUQmc+EJUxPTW1IpcRmZChEOAghlYS5tiVoUJgYiuR0TXYSA1UdHRwNWj2vGWFEQqtCBQlt7\/BiYQQohYSFgpRHGOhAsaxzgMp0j\/wAXK3S\/+RUagGMt0lP73K3S\/wDkVGnisz6keOeGQrxGj3hXhjxzwD3jnhrxzwAG1f8AkJX2fJUaZ4zFrP7\/ACt3kqNMIIA9Mfmk\/a9kHpeA3CAHTI\/JJ+17IMqtASA+oRFWI6K1itXWJKmZi3IA+cWHgFjhDELBwIO6Gz5l1JUztAZjRFnQudaAtIUxls4duyYu2jQkhierZh9EkRV6PH5a075f5TBm1TGS9cRgCrAvgATk3GKASujKB2iknYlT56yATEiejQvMlKUpZgRUksc9lMXgmdKJDEDMDtKSn0iBg94Y6olTaVlmI2MlSuSqCOd51vnrAib0fLJUVBwsANQG8GqNftiK1aPSE3gQGIBADVLjx8In05aFJ6sKK2UsAVSKvT0cIhmWVR\/zFMcmSe8peOP9dlrv\/b69qwkw4S4tlEJci+LPkrdXC3Imo91w+rPlDuqOqJi6r3I67HWucJaVKNboch4fZe2AcAYi\/wCmNHRbMhAxPfHRrxTygiicpScWOv8ArFKda5wopg1XTFdOHOFnkhJ3eYj2SPDa4zVLqT5QR0TNJBBLszd\/sgXZVOIu6MnJSC5ZwG7\/AGw6+HP0XeIzakAs9dxgaLSsLAUoEbhqiGcr5R9o8ok5avQ5NNDugQbw\/wAsna49sEp5BSdreMZ5JUZikiasMpVNQCmavCM41bgbptX71KoRRH\/IqNjejB9JVqE5JvEkITXP01Qlnt9sUHSZqhg4dQ84J\/rfPCvGG\/tO2jKb\/tk\/9YUaatgxC+Mv\/wDMDW4eFeMQOkVqzHOX7od\/eieMUp4pPtgutq8K8YsdLpgxTL5H9UPHTFXqI5n2wNXdJTGt0o7PIwbNomHMDhTZXGMfL0r11qlLus1GBxoY0v7eP4S+BHtiCl0nmk2ZJUe0VBxqqIJ3b6noRdGNOEAulFpCpI7KksRi2sajBiTa0ADsrwGQ1b4e9X1i1YgJaVJcF7xd2wBw4B4qJmXrxLPh\/MRCrtksZrGP0X2GGi0I9Y11y1a6YRZ\/qVesU4JBfOvd3YQs+2pWggPxEU0z5bHt4hvQUMvfCo7VEl9pBY\/G+HpPYd0bV8vad8r8pjQvGc6N\/P2n\/wBX5TGhgtPvRzwyFeKgB0pPakf6qYuNSKPSc9uz\/wCqmCAwjF+tz4alBq0RzwoDHPfEq1sIFaQ0mEoUQpgBi3x8GOXTpzSIB\/aZmXZTU4YRemTAKEvt4xm7NaVLM9RBJZDDZ2IdNVMnLCE0IFXyqwHM90ZnNXvuekump3ycw7D3t4NE8u1lCUgZjzPfAW13jJUkByAon7sE59mJlpmeqAGzqSDwwjMn\/TXlvGi40gly4PAE5V74SK+j7MkoBVia6tjd0dG7rMq+EFvROdQO6G2k9g0y84jnaYljshcsi8\/ziRq27TyhitKySn5yWCzemnUzO8erXm8ZT9H+jxMOAoRh2YqWO3yh2UqQol2AWl8Hoz6o5Vs+pTVe90Ou5PrM4tFFgkDf7YrLSoYg74bL0yrNA4KamQ9GJ52lUFLruyxRnUK5kVb4ETnuX43eLBRJ+TG8eMC5dnaYpQGJVnrU+rZDJGnJOBmoxp2hhz3xVOmJTqabKZyxvD1vZBKC9LqTh\/pj864K9GFfIffOf2YA9J7SlcxJSpKuwASkuHvKPmIXRWmzKlhFwmpL3gMQNaTqjI2k1zQDv90MmJV2Mmx2sfd3xnP70Fvm6\/aH6I7+9KqfJ\/zD9EXTGjlkipGrOJETNhx17TGXHSlfqfzD9EO\/vOr1D+JP6DAxpBVRrgoflSfjfEiUdouH5a4zCelBH+WDvUPJAhT0oLv1f84\/RAxR0P8A4pP2z\/2jXSazCBtjEaMtITaEzFFk3iTmzvsrllGtT0hs7P1qX+yrxuwhVfpgPkfw+IgolY6oa2+NsZ3pHpiVNlXUrBVSgB1h8QNsX5XSSQGF9LNWhxb7GuCiVnQS+wD3xdmyu0KYN4wGR0ms\/rpy+irj9GHnpLI\/ifyq\/TDTBcoD4Z6tkcVpAAcPSjgZaoDnpJJeiyfuL\/TDBpOzlV+8t\/8ATXmkpOW2IshOjR+WtP8A6vymNC8ZHQekZaJs9RvMoy2ZKj6KSC4Apxg1\/bkrVM\/21+yLKlFHjngV\/bcv1J3+0v2Q5OmEmglzn2y1AczhBFDpFNSZksEsZagrW+YGw48qPF6yWkKAyJD3cwHZ9z5xlukelErICgy8gBUfVLhzr58L\/R60tLQHCU3ilT1UtRcpSlsgCDsGqPNx\/Jeu7d9LKLaQCiEpTme1uYmA1u0OuZeQlkppU6qEsINzjUb4nKwzxu9ZXacSwJstlSJ605XUeH9OUXpqAkKUlIvENTPE+JiCWv8AeFnUhPnFsrfJuUYjdgLZ9HlEibexKVeGJi9o1+qSxyia3P1S\/sK8DEeh\/mkPqh+V+cpkLKQAAGApHRPdOTQsaxnQk9HbMUlXV\/RJAKlO4fbsilK0JZyKyx\/uK\/VGkYhBBIJKVGm4+2ByWKWDVFag462Eel5VSToaRL+UQBeSDgpRZxdzJfGKy5sFJMoBwRQ56w49h5xS0vZ0olukB3NXPqnWdbRx7y1252RWROi+mShcs30hQBcA7mgRoXtkg1xbCjAnJvgRobGtKE4h9Q1a98OZid9fhVsmipClJ+RSxOo6tphZmgk36SE3dyajjWClmtBWk4uM7ooN+EUrPNtCyoXzQkOwyLat\/KOkuubN6bsgRaJabgSCE9kMx7ZGRMawaKkfwkfhEA9LaNmKmpmKU90AVdyQoqbDbBmzaYlTA6Sd1HHB3ibJ9XKlGipH8KX+EQ7+zJH8KX+BPsjv25NWckZM25zlEaNKIwIIUMRQjgSz\/wBYuz4mVKNGSf4Uv8CfZDho6T\/Cl\/gT7IhlaUQSQErpiSlmOrbwiQ6Sl6zyguU\/9glfw0fhT7IX9il\/w0fhHsiL+00alb6N4xTTp9BUU3TQtjsBrSmMNMofpSQP2ySAEppqDZ4gQcRZ0jGZK4AA96oCW9cuZNTNvKTdowr3xfsk9JBJcgUF6r66Z+6C4o9K5SRIotKu0MGpUajBSRZ0MD1w9EUbCmp4ebJKmIZaEkHJrtHpVLUpFG0yLMk4EXDi7l6FmOwDnCwELkr+Njs1Bo5KZX8ZR4E69hiFMlK7swOHFAQMCkitaYvE9nkgNSjAOC45QT3+nKVJYAzV7KH9NYmFolDEqLYuk5cPh4baLIlQDNhTf8CI7NcKiil9qpFGxzyweIBvR5Q6601btS\/yqg8mak0BBOwgxhFNVJl0TjdKwBVsiwibQttRLmzGJCrnYQXUFYHH7qs9UCxt4htloEtCl6h\/SASelA+lL5GJ5XSaWVJTdKSosCS4fJ6Zlhxi1GT03pdC1AzEJURgXU4Y4gguzuM8Dk0aLo3ZEBPWm6LwFwYMlhWtXOZ3Vgdb5U0LvEJqaEKfAeMHbHPChcvsoCoY76a2jlOJ5bW7i1PS4cVYw0WdRHa5DCG2SWUoIX2iVKJJwLktTKjcorTJQc0TybvaF4lrU7yHyR+8r+wjzi\/d1wEtTJSpYLEDEKIYDM1wGMUbP0kTdSwmksHJKakCpqSYeGL56P2xRuLAH0VVz9ExFof5lG6Bth0oqcvq2UEkKc3kks2q5uzgzJs91AQks2Dh4k59revSVUlOMdERE31k\/hP6o6NZGdqSdPZN1jw2gjDVXuigJgSBkPLzhilukVc63O7OFWSRWu\/CJtzGLfexJ16Xx4xT0ooFDDX5GJJU+UXCkqcFqXWJ5v8A1h2kVy5iQmVLVecEqJGDHIqMTxw0J6O+mePnGhly0KUAUjgeMDNE2QyVXigKxotQA7gqCq7WRdIRLT2gDdBNCQD2qeEaz2tqWwtdKkgpD1F4kGgNecZzTmkQpYTKmkJOSXAKs3OZjQypgCCkAZ4DZ7owVpBKXTimo3jLyi2\/peI0GiNIEgyZhJBoHxGqvxhGctEwpmKyIUX2F699Iv2JYvJUKOxcRZttns4mLmLF6+QwvEAXQEqwzcjjHPq+vbV9B0vSS8L6m1P8NDbLaQVqTg7F3IbI4VH0cK78IuGVZSbl24pvWNO9sBntgECoT03UKKWJcuKEFt1GxL50ynH30mytEJ6gbt4lKTVshSrDfhgzBjeSYmt05VJiVFibp3jByM6EcIGqmXACAsXmBFxWJxAoxNfs1NK0ISrJOUG6pQQRW8UjjUvjrHsPX2pkua+JPlFRz1i3OYbddHsgpZdFFFZplqp6N9q5OXEXJlos+cqXwUh9mBeLhaGWSQVlhxOoQds8gsAkMkYbcc\/PW8TWK0ybroSkJ2DvHviWTpC8ezLUoa2A7zQ8DGpkYttPJ7LDcO4e2M11omruP6c1T\/ZFD\/KmNLaBNUkhICSR9I68+yPOAVh6OzJakq6wKuvQjF0kelljqhVnoamTAB8NAi2zVzZiZSVEA4tkMy7aotzisCqTTlzEQ6GVWYs4+jupeI49nlEqrtptQSGSUpCQzlyRkwSPbGZGllib8leUo0KiRheBNMEpYOSTljDtMWlnrElgs3UIC1AFamUUqwABcAajnsLaq8Jb1dpi3aZN5F4AMl1KmKF28QDW61RVhmcLwd1Lo+w2eZNvhJSqUot2ixGBcF3Dg6iB31Zmn1EuJaMaE1IODu1KNFSfp6YMZoSMg4A5GN+ca8BuZ0cl49YoDWq77opWnQksYWkBQLg3QWIw+lrgPM0ve+mpW4E+EQG0k4S1neG8WiXur4RrNJ2iVOWl1JCAGVkoqJGD4ANi+uKdokykduWtZIAUO0QACwBJBpwqw3QCRMmH6AG9Q8ngtYZspKwszJhcC8kAhixdlBQvB2xGW2LO\/wBs9fxz8C9nlrNnv9YorXVNcKswvOTR4ETLPbfrHZfJHIFoSXpN0lRM0pvKF4y0lHZURilDuAA9dcLJn3yCiaktiEBNd4LkcxG\/TmEactUxKAlaGUo4kVIR2jU5EhI+9FSxpLAaqcol6SLJnS0qWVdk4t2QpaQMAMbueqHWdLRKRouitnqtTZBI4lz4J5xpICdFz2F\/a\/6iDTwadHRzx0ADlz0k3RicXd\/cIdNUMm34\/HuhqJEsKJCFVx2bG1Q64lTgOmuPmzRm39OWusSGc0OokY0FW+MYsmcKJr4dw8IqSuzfrVLAE\/YSX7+6BK+kU8UFwbkxcajToepA7j3QyfaAQKfSQ\/BQeMmdN2hZbrCNwA7wHjS9RMElCzh2FXioa0mu+vOLiraVhyE7X5FmjHJMaUaRlvVcsmoAC0nJsAXeB2l7B1K7nZwfB3fXeJ1QvK83AayzCl9SVUINWLHxJiDSImXim4si+D6JbEHIVxJ+7tgrZ5t03ilLki6kAC8cHLZbYu26YBMUFGoJB3jFqa4x1Py19BbDIWqYJnVqCg5JIb0Uulgc8qQSM60n6ShxCYtaMtKOsS5o4d8AM3fJnjQWqehBvS5YJYdsDAZBMXjnUvr4B2bRtrXV1Aa1Kb3xa\/u1OV6c0c1HyEXDplYxS\/KI1adGYWNxGtsAY6eETyqOX0SGcw8E+0x1p6Kpu9hZvfWZjyDjfWFtPSNKWa+pR+i1WzJxp4xNadNJDpSpS1DJIHi1d4eFkhtZSaZsmYJUxPZKgbuuhq+BHsjSp0pdSSK3QS2dICS7QbRPShWCixCu1d3ChSePsjQWrQJbsm+G3K4NjExd1PZbUVAFSmJDsAAA+Tl37ouJQD9JR4t4NGflKUAxDs42hiRUZ0ESLtSmSEKZ1AHWA9ccC0NXB0yU6uZJ8YgtEtDHAFi2Aq0RSp6cCl99fGLaFIOAHANFRgpVnVNnoCh2D2iWU11IvFyQ2FMc4v20mdOSl\/SOOoAEk8g0HdI2dCAqYEsoggmtQWJpg9BWAmgZDzVzMgAkfeLnuA5xynOemp+wS3WFCZqksSAfpEqyrjHIlJGAA3ACLWlvnl74hEYrrChUdHNHRFIRDpYjmhUwBjoYXkF8ps4D\/cJ8zAydZJapClFCSU2dJBaoUFzAS+ug5QmgbcZUlN3tKVMmEp7WHXKDUcA5gxatxAkr22cHnMWfOO0rhVPpD0elS0GZLKgbyKEuKrSl3NdWcEtG6ElLQiYVqN5CVMCAO0kHUTnEvSRX7tNOoBX4VBXlEuikXZMpILshIfYABhDTFrQ9yVNnSwCE\/JqAe8wKSDj9ZJPGDAmSzmOIaMuqZdtI+vJP\/wAa3\/8AsMXDPMPI8R3q0HBuCvfHQB\/aTHQ8oviFJtc9Xo2ZX3lDwbCLKZNsOCEDYym2VJH5Y1SeMOvRvHP0B6GVPCFgy0FV5QUp2qAElqGlO6AM7o+q+oKV6KL5unKoDApqXTrEaiyWgJM1NS0wn8VeFTGd01pEmabhqUBJIWQPSUWYJ7WOuFWHWXQEsrCajtFKi7kKCbzMSRg3ODds0Sjq0hSlrAVLSAo0AKgkskMMCYAaKti0D0Fk3yXBWcUhLkqck0jQdeTdqWcFQOwvvyEWYXU40PISOzKSDlSKNts1snekmWBWiggtuNSIKrtiHAfMZMBtLswh9o0pJDkzE8De8Hh6S6xGnLPMlFCZqkns9m7S6HNBQZxVt1sQDecqB+kKgnaddc4J9I7dZ50xJSTMupYhILu5OBaKEuzkuUSFMcb\/AGH5gxz6Wa7RekpIUDUnK8ksDsZVeIjXotFA8sl87q2rtCTGXTZpwKexKTqvlRDnAOnPU+MGUC1kfPBI1JQDyKqxqXCyr5tErMAcQPFop6QtMlCTdSCoigcAUwKiMhFddkWtV0zpyvXdZCQNTBqnVljqeWRoOSmqZaXOsP4xfIxmJhvK7UxNcQmr7Oy7CNdZNI2aUPk0TFOQ6gkuoksHUtnqcNsTS7JdwQBuAERaRlDqlnNIvcUMvyjPxfoTZZcxM0TBKwVeZSkp5sDBmZpW04JQjbibozUVEgMNxh066kFRoBt8BrMMDqFAyAcxVZGvYDlDaYE2nT6euKJy6sGVdCU4kVOIwzweLq5aVMdxBGOwvFbTehkzqkkKyUMW1HWKxmkG02M3fSRklRdJ13SPROzuiasbAKUn6w7xwziaVNBwMDNF6XlzgwLKzQcRu1jaO6CBlg79YoYom0kp5RGqsVej8tpTs14qPfdHckQ+1oWZZA7VNx9kTaLSRKQCGN0U3w\/K\/hltJ\/PL+0YjAibSQ+VVvPjEQEca6x0JDmhIikjhHQhMQFOj8oS7ODmb5fepR5RBNmHq1Z\/Ij8xIEcpaxJCQKMcaYuccM4GrnkAg0cJFcKHIiN6z42DOn5j2acGxlr\/KYn0csGTLIzQjbikQNt9qeSrAuGyPfBaxWW7KlpFLqEjkkCLKzecVLa4nSTjVaeBRe8Upi0ubFW1J+WRX0UqPEkAecPJMNMSdYIWKxVsjog2AMKTEYMQW2cwujE+EeiuCoqWCpShmTsx18IaiWBgANw846+4hVYY90YaKcWHNiYeJWs+PwIYlt5iwFjAVPxkICCZJSpw2IIeueqA6LEhCkkS0lRUpJCq9oJKnCjg4TB8IOuBulBcUlX10E7CFBKj+BSuUFiWStBN2qVeqaEbsjwifqdUOnSErDKTuOBG44iKipsyWbr9aNQ+cSNuscoC2ZIYgsxxGsboqVAaU6qtX6OHokkXgK0fjlE1nUmZV3Hqg\/mzO7CLjsKAQFOQboYS17Sakk4kkO5MTpnjaN4I8RDyqEcxQ8KfABojtSk3FXsCCOYZmiOYtKQVHLE+XuiKSlSlBawaeih\/R2q+t4RBFZpZm3VKaiRhgHAq+ajryB2xcWWpyjpMu6ABhqO9z4wjjE0iDlPnnv9kVLRICgQpIIORqDvp5xaUBg3GvxkYagJagqfgY\/HKCsjpPQBHblXtbYKB+qRj474Zo7pCtHZmi+PWFFDeM\/HfGumFw1PPZ58oFaT0KibXBdO0B+bLw8jPnxd\/YhYrciYm8hQUPDYRiDvi3ZFdhP2R4R51abFOs67wJQdYwO\/IjYdjiNDoDpHfaXNAQtg1WSrKj57M+6LLpYgt\/zit58TEYEWNIpAmKfXAqdpqSlwCVEZAHuJpHPLXTZPq60NVAs6XUqoSEjbjz90QWjSThos\/jrN\/ln4HESlq9FJI14DmaRBpALlFF5IZSgkEF6kE+AMR6O0lMuPecOzH2xY02p0ySzHrUFnxooU5xLxjXPcowlhLAgepOO3iD5wVoR8PFSbLGw+Mc7HWWUOtCEkMkMdhjVCYGFX7oziyOXxSJTaTFnWHXOr01jMWahkgeJxjpaKRBZVG4o+sYsSBSvOLKzYaZZhYmcb46NM4OFbBzh5QNVNKjeZ\/LVXCJbdPHouzVOvZ8boq3yaPzPhjHe15onM6lBXY3i0MuPjTmfGOSm6Hp4Nu98LeURiBqAPnGVKUuaJLBsaDhjE6ZoYBwDzHuimJKfpFXPx1RNLkgYCmUA\/rE+tePxuiLSF1UtW5wGxpgDm4ccYkKTmYQoGuArot4XgsJG30jrAeiYtybg9Eg66u+85mI06jUfG+GmSg\/RTyAgHzrKFG89xWSk0PH1oiNqWikyqfXTh94YjhEgSBgabMO6HFescve8BIhSSHcEHBi8IspFSafGWJgfNkpS65ZKDmPSSreKNvA1xFItJWy5gKWwSHNfWJx3RNXBK4VqBNAk0T6u0n1vDe7S0yrFNNpegFBl\/XEQ9SyNfBm9sNFkXsyANkIU6gTtpEHWD1q5xwmtnSGqetTU7ySB4Q6XOBD0UMHfVvw5xCSCXDE8PF\/DXD0zi7MQ2bf151FIgkKnwF3gGPnDBLzYPk\/B\/DuhyfjLuzEKg7m+M8zAQ2mzJWGIcHHBtnxjSMzpbouWJl9oeqceB+OMavrQH17K58hz8IhnKXk4HHx54c4lWennnSGSVrTQlpcsHZ8mkF+IgN1bR6VpLRaZgckJXkseCh9LMYvtoYyGk9GTEFlBL4gpdjuh5WNTmUIRMMcdYh0yURQiEwGIh51ZxHSZpSaHhF9ekVLMtCsAsKigEiJAKjeIz5L4twmYRSOE8OxioVmhfLhHBT7IzV4OtRc4vv9sVVnbwMJbrwir+0khjXu74xjtKOWRfybYVzi5Jw+GgHY19lgaani9LXdFHixmryo6KotOuOjWsiKlINXxL3st+rnEqZgbsh9wLcKRRn2gi6ABVJPKHyiVByTjr2R215sEOsDba7G84iUz1PjTvioCxSMX18YnUjuhoemakUPxx89sS9Yk51bIxWlVq1YU1UBsJ7wIaYmM0QpVs+N0R57gfKOJfHdSKHKfNh4wqSMMd0MVLDxHePeIgnKvj+kNK8nO\/3Q5SQIhmFgTqHDP2QDVpJz4Uht0gV8n7oqqtRqWFDEk+aUimzF890Z1rEyltnuGfuhBMJDtvejFuffEErtKDhyxL5+yOTMICmoxHlxziLiyJj4imx8eFdde+HpUckkcIrJnG\/dYZB6v4xLJrjr9oqMDhAWL7bR3DKtdeuJUretK1fHdESV1OzDZQmkSKSxxNamsVD1yklia76JO\/3vE0xYyLnEtqaKstL68szmHryhEhyAc88wzChiiVExmzxxocawqVhnSx4gUxEcU0I2ccMD8ZQ2bOIwb0gOagnwMA66HwY8SXphzitabMhQKFAEB8WfHHWDUxIpWWRVd4XiOdH3xHZ0Baa0AegNDQGuvGIrP6V0PcqkXk4s1U1ApmcRt1iAKpAjfqVX7wGeYHhlAbpBYkBImBLKerYHsXq8oxefzHTnv8VkV2fVDJcuo34QRVLHf5PESQ6oxK6WCpXqh8maRFcGGmkVnE1o7QOXhyimiQ2NRrxi0RSISaxluFlpAi2J\/GKYNIeYFEETAYSKAMJFR\/\/Z"
    },
    {
        "property_id": 10009,
        "title": "Historic Firehouse Conversion",
        "property_type": "Cabin",
        "monthly_rent": 1950,
        "bedrooms": 1,
        "bathrooms": 1,
        "status": "",
        "city": "Capital City",
        "description": "Charming 1-bedroom cabin ideal for weekend getaways, featuring a fire pit and hammock area.",
        "image_url": "https:\/\/cf.bstatic.com\/xdata\/images\/hotel\/max1024x768\/567633932.jpg?k=e3e6816792d8429f11d653ee98bf25e51233023ab5a6d55bae8939b7579dbcde&o=&hp=1"
    },
    {
        "property_id": 10010,
        "title": "Large 5-Bedroom House for Students",
        "property_type": "House",
        "monthly_rent": 4000,
        "bedrooms": 5,
        "bathrooms": 2,
        "status": "",
        "city": "Oakwood",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSslzLsJbGHymTqpDuvP5384UKl2oWinqMfPw&s",
        "description": "Spacious 5-bedroom 2-bathroom villa located in a peaceful neighborhood with lush gardens, a swimming pool, and ample parking space."
    },
    {
        "property_id": 10011,
        "title": "1 Bed, 1 Bath in Gated Community",
        "property_type": "Apartment",
        "monthly_rent": 1350,
        "bedrooms": 1,
        "bathrooms": 1,
        "city": "Springfield",
        "status": "",
        "description": "Bright and airy corner apartment with floor-to-ceiling windows, perfect for couples or small families.",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcRlM8RCLCQNL0D_EDB76pRDu9WQSUKnt7UEzQ&s"
    },
    {
        "property_id": 10012,
        "title": "Spacious 2-Bedroom with Balcony",
        "property_type": "Apartment",
        "monthly_rent": 1800,
        "bedrooms": 2,
        "bathrooms": 1,
        "city": "Capital City",
        "status": "",
        "description": "Elegant 2-bedroom 1-bathroom apartment offering premium finishes, a fully equipped kitchen, and access to gym and swimming facilities",
        "image_url": "https:\/\/realapartmentsbudapest.com\/wp-content\/uploads\/2018\/05\/20220526_01.jpg"
    },
    {
        "property_id": 10013,
        "title": "Quiet End-Unit Townhouse",
        "property_type": "Townhouse",
        "monthly_rent": 2500,
        "bedrooms": 3,
        "bathrooms": 2,
        "city": "Oakwood",
        "status": "",
        "description": "Elegant duplex featuring high ceilings, a private terrace, and easy access to schools and shopping districts.",
        "image_url": "https:\/\/media.gettyimages.com\/id\/2157215649\/photo\/single-family-rental-homes.jpg?s=612x612&w=gi&k=20&c=uL6bF9J7ZNwVLu64eJGRQn3jLjOrTmGytgA6zSVTTPU="
    },
    {
        "property_id": 10014,
        "title": "Rural Cottage with Large Garden",
        "property_type": "House",
        "monthly_rent": 1600,
        "bedrooms": 2,
        "bathrooms": 1,
        "city": "Farmdale",
        "status": "",
        "description": "Charming single-story home with large windows, natural light throughout, and a cozy fireplace for winter evenings.",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcSYvgmscTSkuMOSb8XvfTy0Piq7Qh-9xFFv6g&s"
    },
    {
        "property_id": 10015,
        "title": "High-Rise Studio with Pool Access",
        "property_type": "Condo",
        "monthly_rent": 1550,
        "bedrooms": 3,
        "bathrooms": 1,
        "status": "",
        "description": "Upscale 3-bedroom 1-bathroom condo offering city views, stainless steel appliances, and a private balcony.",
        "city": "Capital City",
        "image_url": "https:\/\/assets.newsweek.com\/wp-content\/uploads\/2025\/08\/2653160-florida-condo-buildings.jpg?w=1600&quality=75&webp=1"
    },
    {
        "property_id": 10016,
        "title": "Brand New 1 Bedroom Apartment",
        "property_type": "Apartment",
        "monthly_rent": 1400,
        "bedrooms": 1,
        "bathrooms": 1,
        "city": "Springfield",
        "status": "",
        "description": "Stylish apartment with smart-home features, gym access, and underground parking.",
        "image_url": "https:\/\/realapartmentsbudapest.com\/wp-content\/uploads\/2018\/05\/20220526_01.jpg"
    },
    {
        "property_id": 10017,
        "title": "Executive Home on a Golf Course",
        "property_type": "House",
        "monthly_rent": 5000,
        "bedrooms": 4,
        "bathrooms": 4.5,
        "city": "Oakwood",
        "status": "",
        "description": "Classic countryside home surrounded by greenery, offering peace, comfort, and scenic views of rolling hills.",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcTYE4fG6zKECnaJpcNNKya4xeQ5Uf0RTPxj699f8uSVUgjtR-WIAnYd1v4mnYfoswC_a2U&usqp=CAU"
    },
    {
        "property_id": 10018,
        "title": "Small Cabin in the Woods - Pet Friendly",
        "property_type": "Cabin",
        "monthly_rent": 1100,
        "bedrooms": 1,
        "bathrooms": 1,
        "city": "Farmdale",
        "status": "",
        "description": "Cozy lakeside cabin offering breathtaking sunrise views and private dock access.",
        "image_url": "data:image\/jpeg;base64,\/9j\/4AAQSkZJRgABAQAAAQABAAD\/2wCEAAkGBxITEhUSEhMVFhUXFxcVGBgXFxgVFxcYFRUYGBUXFxgYHSggGBolGxUXITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGxAQGi0lHyYtLS0tLS8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf\/AABEIAOEA4QMBIgACEQEDEQH\/xAAcAAABBQEBAQAAAAAAAAAAAAAGAAIDBAUBBwj\/xABJEAABAwEFAwcHCAcIAwEAAAABAAIDEQQFEiExBkFREyJhcYGRsSMyQnJzodEHFDNSgrLB8BYkYnS0wuEVNENTkqKzw0SD8aP\/xAAZAQADAQEBAAAAAAAAAAAAAAACAwQBAAX\/xAAsEQACAgEDAwQBAwUBAAAAAAAAAQIRAxIhMRMyUQQiQWGRcYHwM0JSobEj\/9oADAMBAAIRAxEAPwDz+\/AYpo5Ruri9WrR\/MUUWV9Qs+\/7FiOE72PHvYodmrSXRhp85hLD9nT3UUHMV9FnEgljKnYq0TlYY5CEyZrVNG3pKiYrEaYgWOAPQkepStC6QjAKrioXq1I1VXtQMJEaSRC6EIQ4BTRtUTVcszKrUY2SwRcEabPXAABJKOkA+J6EtnbiDQJZRuqAdOs9CrbRbQ4qxxnm6E73f0VEUoq2IbctkT7RbR0BjiPQXDwb8UDTS1KfLLVV3JU52MjGhjkwp5CYUpjDhTU6qRCw0aUyieUwlc2cccmFyTnKvLPRYcSPeqs04CybyvyKPKtXfVbmf6dqxPnVotBc1p5NoyOuLP89CYobWwXNcIKfnQSQv+j5\/zHpLvZ\/l\/oy5+A3vaz+UHqu8WoVs9YrUW+jKKj1m\/wBEf3nDWRvqv8WIM2nspAxtHOYQ8fZ1HdVBB06DfFm7Z3K5Gsi7bQHtDgciAR2rUjcuOLLAFZib1\/nrVVrlZhcjiCyyAV2p4d39UmkcU6iYAQSOVVyvSBUpEMgkRpAJBqmihJQhDWBE2yjrO1xdM4AjzQQSOskBZFrumaNoe9hDTTOhpnoqrX0RL2sW6kgq2h2jMlWR5M97uvo6ELyykqN0iaSulNs2MaEXLlUkkIZwppSconOQs4fVMNFG6VV5rWBvWGlh76KtLaaLBtu0Ta4YgZXcG6DrdoqJsdon+mfhb9RmXe7ei0+djNXg0Lx2ijacLSXu+q3M\/ALMe21T+ceSZwbm49Z\/+dS17vudrBRrQPHvWvZ7B0Ieol2o3Q3yD133GxmYbnxOZ\/p2KS4bJWS0dElPcjCC7+hVNnrv8pasv8Y+JWJyknZzSTVFb5kkiX5ikg0sKzJuq8xaMOLKRrXte3ShBYK03A+4gjcqd+2fmu6j4KXaGzOgnFqhFeaTK3i0YcTu4ivqg8VNeEjZIsbDVrm1Hd4pklw0BHwB1wSYC+E+g7L1XZj8QieGRBt8PMUrZWivokcQdPf4qeHaFw86KTsGLwKOnLdGJ1sGjHq1E9BsW1MQ87E3ra4fgrtn2ngP+IztcB4rtMl8HNoLmyKQELAhviN2jgeo1VuK3tO9bZlGk85Km9I2ocVFyoXNnJFmCOpRtc91x2eP5xaMvqt39GW89CHNm7dBE4yStc4jzAKUrxNSm3zfj53Vcctw3AIotRVgyTexNtDfbp3Z5NHmt3D4npWLiTHSJuJLlKw1Gh5cuKNzlzGhsKiWq4XKu+aio269o4xV7gB0nwG9at+DDRkkVG02xrQSSAOlYkt7zS5Qx0H15KgdjdT7lBY7oMr3mZxkLXACuTRzGuybp6S2kuTrfwSz3655w2dhefraMHadexUbTd0r8BnkLsT2twN5rKHXpOmqLrNdY3BSW27fovas\/FCsm+yOcfJmWO62tFGtAHQKLSgsC2I7GOCsss4SnbGcGfBYlowWQKZjF2W1Mj897W+sQO6uqJIFssxwCiz7sAxz0\/zD95yrWnamBgNCXU30wt73UUOzFq5TlpPrSE01pizpXfrqnU1Fi7TZvVSUdV1LsOita2VkZ6r\/AORB9rj+aSGM\/QS1wHdG86t6AfzoSjS0\/SM9V\/ixZt+WNssb2PFQQewgVBHSEV0Yee7TReb67PEJRWcrl4vdzIZfpGSRiv12Yua4eB6VswwLJbJI2O7M9llKr3bd7XMzaDz5NQD\/AIjkSR2cLNue2wBmF0sYdjkyL2g5yOIyJ4IYt06OdXuVTcEJ\/wAJvYKeCezZ1no8o3qkePxRHA+I6PYftD4rVstmaeC1Sn5OaiBX9hyjzZ5h1lrvFqhs8Frw1E1cyKOjG5xG4jgvSm2NvBZ8FhbyYy3u++5HqdA0rAkWi2t3xO7HN+KcL4tQ86Fp9V4\/mARVPdw4LPmsQ4IOp9Bafsxf0lcKY4ZBXLIB2evok8CpG7Uxeljb1sePwSvGyUdF7T\/qkP4Lj7Mt1quDtL8ko2igOkje8VUNq2gjb6VTuDecT1AKlabKN4Hcs67LOOUkAAHO3eq34rVpqzHZffbrRMcIHJN1qaOea9GjdOlW7vuZoOIgud9ZxxO7zp2Kew2fnn1W+Lvgt2JrGCryGjiSAO8oJTfCCUVyyKz3f0K3c9gAdNX\/ADB\/xRqq\/aiytOFjnSu+rE0vPYdD3q9s3bOVEz8JZ5UjC7zhhijFDwPQuUWk2zNSb2NqKzAKtemFojJoByrMzkBk5Ww5DPygTltmBaaHGAD9ly2G7oyWyNC23\/Zo9ZK+qMXv096x37VTS5WSyvfwcQSP9uX+4LRsVzEzCOGCEGpAkfWV+Qq41kOWXSiKz7KzSBnK2p1HCoDOaKFtRVrQB3FOjiT4QqWTywHls95SZzzx2ZvAvDT3MqT\/AKkyC6rMDzpp53bxEzCD1k1d2goxj2es4tcUUjasaHtOLXIFwqan0jrXfqi+w2OBjnMZFUjDgOBxpluc\/Id+abGD\/QFyR51cthbyjSywhrAc5JDyrwONXVLc6DtVTYZ9Y5van7oXo8krjZyXMpziRU5VMpNWAVzoaHQa6rzHYE8yf2x+6EvIqi\/2Cxu5BZVJMSUpRRbtEA5Rnqv\/AJFUvCDmu6j4LQnPlGeq\/wDkUFv8x3qnwVEkhSZ5zthYWuMWIf4jBwNHaioUcNwR7jKOqV4\/Fam1TKmH20firlnjSnJpKmHpTe5kx3D9We0j\/wBpI7in7D7J\/PIWhogxDlSXTMxE0mLGioFVvxRqT5KbUyOHE9tR5YaA\/wDkOrr0JuBuWz\/nIvLUVZDL8mzg0Ex2UggHLlWaloz5wp54VeybGAtDmQxgGmlpcw1IFcnk5AnVehOvGFzWNbUGgblUZh0Z9E680rCjtRaxo3UrqR06dip0E3UB2XZ2WMj6RoLmsqy0NeAXdHJ55Zpl0vtQjpG2F7Q6RoL3va8kSOBrRhbqCiR0xIYCfTaev8eCyNmTWA+2tH\/PIlZvahuJ6iCW0WvfZoz6s9PvMVKa2zjzrI\/7MkTvFwRO9qryRqRv6KUvsCrdb3F0WKzztpJXzWGvkpBQYXmpzr1ApSXnGNWTD\/0yEd4C3L0h58HtT\/wTLslnWtqlsYk\/ILy3lCfSI9Zj2\/eCz7rtMfKSEVdV+WBrn15jOAyzB1RTaYVnXFH5Sb2v\/XGuTjT2Oadoi5OWWUgctC0RtJAEfKOrJhBBqcNC7jVETNg8DHTSQY8LS6s8pkdvA5goPOHcu3fH+tP\/AHdv\/OF6Le2E2aXzq4TmcQp5Q7tNDwVONXGxOR0wcu7ZZwjaeVbE11ObEwRgAx49aV3ga\/1xdmfOtf73L4MXoV20EUZwfV53NFfICu+vTn1rz3ZnzrX+9y\/dYszxSjsZik3Lc3AhX5Rv7sz2o+65FSFflF\/u7Pat+65T4u5D59rDO4m\/rDKmgxSGulOZqT2oghEWGGpDuaAASXkO5PhnQZfnJDVgtHJzMdkSHSaiurct25aIvd4awDRoyAHFpqc16EOCOb3OxuIvBpAqRjyAp6FDQHRbsclXPAY7CcIzc0Vy1q1xNOpB09sImZIa1o47t7QOw0PBXrrvKR82QqSDqaCgpnkNOpbFMGTRoSSO+bkENIqaOBJp5U5AUFD07\/cvMtgPMn9sfuhelvxiClRg3Uaa\/SHfiyHTvXmnyfeZaPbHwSM\/b+B2HkKkl2iSiorLdoPlWeq\/xYorceY71T4FOnPlWeq\/xYo7aeY7qPgqJCUCG0w50HtovFacDVnbRjOD28XitaEJMuEMXJLGF35JvoTU4R5XOo1+cyYteiicxc+Ssn5vp6U38Q5O9Ny\/55FZ+Aydho3njRu9v14+hVrljaYGl5FcdKc3TGKE14jNWXuPM6m7\/wBqPoUVzuPINy9IfeariQgvqBgZVoBcHtpTCK5aZaZ+KEdlfoD7a0fxEiNL5Jwaf4jePAdCCdlD5A+2tH8RIpvUcfz7H4UbDkxwTyVwqMpMm84+fZ\/bH+HnUz41y8\/Ps\/tj\/DzqaVa+EcjItjFk3C3yk\/tf+qNbdqCybjHlLR7X\/qjWLhm\/KNawj9af+7t19uEaXtajyLmCrqtP1aDnkmuQPHTeQguzD9af+7t\/5wiAMdgJqaBv4EfircC9hJlfuLNktpMYYagUG8t9DDu30Qvsv51s\/e5fusWzYW1aNdBv6Fi7Mu59s\/fJfuRrfUqomene5uoW+UP+7s9q37rkUEoW+UL+7x+2b91yjx96Kp9rCdjvKM9pJ9wq2Dk3s+6VSFeUZSvnvrwHMy6s1bBybnw3n6p6V6eLtRBl7inaxSQ5a1PR5jRQZ9HvV64gTMPRyOYp+zlQjOqr25pxCldHaV4BWrlb5UB2lD+FOreihw\/1YMuV+hrcmeQJDiRU1qdDymgoAvMvk9820e2\/BemuibyJNADUjXXyhz68l5j8nx5to9r+BUuft\/BTh5C2iS4kobKyWZ\/lWeq\/xYm2x3Nd1HwVWaXyrPUf4sVW8LzjBMeNuMtcQ2tTzWknwTm7FIydoNYPbReK1okCWS8nSmKWR1STGTwHOaTQbgjphQ5I1QUHZYaVW+Te0BlmBcKjHONR\/nuO9TNKGrs2Vs8jC9weTjlHnEDmyuaNOgLcM9NsHJHVsejOvBnNy0A3jcWmnuUNgvBrYw0tbWtc3AcDw6EDu2RsW9h7ZHfFVLwuS64Gh8rAATQUc9xJ6ga7k\/r29hXRo9Ctt4sc2nkxzmmuMbqbsI8UNbKO8gfbWj+IkWRZ7nutzQ4CKh4yOB7nOBCt3FeFmigawSxNAdJkXjLyr6anhRBkm5LgOEVFhJiXHOWQ7aKyj\/yIux7T4FQv2msn+ez3\/BI0y8DbXkuXk7n2f2x\/h5lPI5BN97ZMbNGI2iRjTylQ4gl2GRhbmODwVoHbKyloJe4EitMDzToqBT3onjnS2BU429zWtJQ\/d94wxTTtkkYwmUEBxAy5KMVz6QU2fa2zbnO\/0O+CCb5tbZJ5JG6OIpXLRoH4IseJu1IyeRLg9OsNvhdaXFssbgYA0EPBGIS1pUIvbaWmItAJOGlaile9fPNmDS9uKhbiFa8K5o+ui4bM9oczlIzxjkcPGqc5dJUKrqOz067pWtY1rsQIpXf6FD5pI1QjsuedbP3uT7kapN2flb9Fb7SOh7jIO6oBWHb71nu\/EwOZK+SaR73OaRXyUB0ByPPO\/cslPqqkbGPTds9FL0MfKA79Xj9q37rkOs+USX0oGHqc5v4FVr82wbaY2xmJzCHh9Q4O0BFKEDjqlwxTUkw5ZItHqcDazR+tJ\/xq\/Gw0Z001ppgPShS79qbE\/C\/5xgeKmhIbhLgMQ5zaHhwW9ZL0jdTk7RG6mY8x24j0TnkVXDIoqmTTxuTtHbyZzwOh2vSArd0MAlzGWE1y40+CqW1jnkEUOTs6lgzpprT3qxYp3seHYA7KlQ8DPiMQRQyRqr+TJ43f7Gq4s5E+biq6lKacp4rzL5PjlaPa\/Feif2hVhjMbwSTzhhLc3k0dnXfuB7l5z8n5ytHtfikZ2nH8DcKqQXpJtUlCVnnm0m0kvLSRM5nJ0ZiGpD2tcerRDcdtdG8SjNwxa1zxMczPsco5raZppJCKF+F1Bn5owjPqT3R80ngK92a9BJJURttuyW7LJJJGA051cynTiOXvC3G7PWnfaXd7z\/Ms\/Zm9zE55MJkcXuc0N5oBfqQAD1DhVegzyFkZkc2lG4iNaZVpkUnLKcXSG44xa3BJuycztbQf9Lj\/ADqYbCmhc6YkAEnmU0zPpFQ3LeE77VGXTPo+QAsqMABPmgUyHv6V63YrKCKEZHJdWROrOTg1dHlDdjod8r+wNCGL8sccM5ijcXBrWkl1K4nZ0yAyph70ZzNnY98ZeyrHOb9G4eaaa8p+CBGONotJDj9JKGkjcMWHLsCLE5W7YORKlSC+5NlIpYY5ZHSVe3FRpaBQ5t1aTm2h7VZu3ZOzPYHOxk1ePOp5sjmjQcAEQRuAoBpoF5\/+ktpa9zYnDA1z6ANBBBkccRJzNapcXOd0w2owq0Fn6IWQeg4\/bd8UnbN2NoryXe55\/mXbiv4WhmYwvGoGh6u9WLVIlSc4umxiUGrSA7a+wQxRsfHGG0kANK5hzXcT0Kjs7brKwuFoYHNNMJc0OwnOvYajuV\/bOcck1u8uxdjQR4uCEi0U0y30\/rvVeJase5Nkemex6SLFBIzFZobPIeHNZlx80\/nesW3wtFRNYcJ0BGhrl57Rr0ZoMZaMBrGXtPHFw0yARPc+3FqYOe3lWtpUkGorxI8MlnRcd1v+9BdVS+irYbgdMXsoWPqSGkjEGVFKsdRx1GauxbK2uLONxHquLD7j+K0r324Bja+zgRyV8oC0EltMsyNKqSwfKAaDl4Q4fWacJ6yMweoUXSeV7r8HJQWzKAvS8YPOdJQfXaHt7XU\/FYt\/3s+dzXPw15x5oIGbWN3k7owvTbFf1ilZjEgbTUOBxdgFa9ipWqzXdaTmWF2gqTE499MXvQxm4u5RCcU1tI8qqliW9tFd1miryTpCQc2uGGg6KgEdoKy4LEXAOYQTrhxCo69FQpJqxDTToPoLqszgA6FmgFRzT11bRWH7GWR4yxt6nV+8CvNomTxZgSM6RiaO8ZFbtg2ptbB9JiHB7Wn3ih96neKf9shynF8oJ\/0Mez6C1vZ0ULfexw8F03besfmWnGOl9T\/+gPiqlj27cKCWEHiWOI\/2ur4rZsm2Vlf5xdGf2m\/i2oQXlXIXsZSF7XvH50QfT9gO\/wCMhO+T8OAtAcC08oKgihBoaih0RJZbyhk+jljd1OBPdWqzNnvprZ7cfcCxzbi00aopNUzdSTapJA08TuuE8oyoyeHAdNM0T2e6MZEYFS7KnRvJ4ABYc9sZy0IjGUbqVOrsRaCT2DTcia5dpYIcTnNkdI7Koa0tDeAJeOvTcFdk1colhXDDmCANAGtAB3BPnsjJGljxVpyI4plmeXta\/nNxAGhpUVFaHXPtQ9tdfjowbPGXB7mgl4IDmAnRuWpAOe6qkhFylSKZSSVsIbJcVmY4ObCwOBqDSpB3EVRVYBkvGdjnyi1RgzSEFxqCQcWRJDjSpzXslhKoUXGVN2I1KS2R5p8ojhBbC4tkIla1\/NdhFRzHAdPNB+0F5xbZnGYvbiDWkFhIFWnIipbrzhlXgvXvldsrfm7LQ5pdyT6GhwnDJQHd9YM715mLPIH82IRkgtJke10Za8UNePHLeAnqluKdt0F9vvWlkM+hMYI6HPADfe4IGsTcMWLe40HUMvir+01sY2zw2eOQPpTEQQcmNoK04k1p0LKjtGIMArRo9+8peGFL9w8krYR7IV5WQ7mgf6nf0a7vRHO5ClwW58UZwwPeXuLq1DW0oAACddD3q3LeVqIqIWN9Z5d90JGZXO9vyOx7RIdqInOY3DQkPGWVSCDUCv5yQrK5zTRzS1tcgRn36Lbtklpdm5zBQ15rSd37RQ\/apXOPOcTQ8APBPw8VYnKt7InvbWtETbAazdTMuPnIUMZ4ZIs2BFHTdTP5kef+mwcPejSv26YH2d8zG4XN4c0VJFQRpo7dRB9klIGR7F6BfsodZJS2oy0PrN0XnmCh6DmD+CV6dtxdjM6qWxpXXbWMnjkkYCwGj25EFpqCaHeK17Eb\/wBjWK0DFZ5iwn0XHEOzEc+xxXmwOahjmfG4lji09Bp\/9TXBvh0LUkuUelO2ctrCGMcws\/aJLQPUc0ivV3qS2MZZwwPsnLSUq58MZjGZoMNAc+OY\/BCt0bdWiKgccbewe6lO4DrRvdG29nnoHUa7hoe469hKnmpruVr6HQcPhmtYbsY6Nr2sczE0HC+oc2uZDgd6rW7ZmF\/nRNPSBhPe2hRJYJmPHMcD0aHuOYVp0VUlRfKG38M81tWxMZ8x72dBo8fgfesa17I2lmbQ2QdBwnudl716xLZ+hVpYg0VcQ0cSQB71qyziY8cGeNWmwyR\/SRub0uaQO\/RPsl5TREmORza60Na00qDkV6Xb72s0Y5zwe6neaA9lUD3xellmcWRQtDjU4wC2h4kile4hOhlcuUKlj08Mj\/Sq1\/5v+1vwXE39G5P85ncUkdw+gKmMsOxNolIq5jRxq4\/yjxRLdOw5Y9rpJGua0g4AwitNAXF2lehGkcQaAAFJRTPPNj1hiiKi8uvqblLRI\/i4gdTch4L1NzciOOSHYdhrN6RldXjIR92i3DkjB2zssHJUgf2UytUPHEfA\/wBV7BYyha7Nm7NC4PjiAc3QkucRUUNMROdCiWzmib1FKVoWoOKpkG1FgFoss0BAONjgK\/WGbD2OAPYvGrvuaKlSwE9Nfivb5HrxnayWWC1zRtDQ3FibUE1a8Bw9IaEkdiJpz2Toy1Hdow9rrCI+Tc1oDTUZAAV1FadHgspsuRypkAtr+1XUcJHUNDhIYC2tMg4HMCu8V10WHA4SSc7IOcK0FKA6kBOhFxjTEzdytHo9gs45KMAegz7oTnQcAm7OSGSzRHQhuAjpjOE+C0+TxdBC8fJak0z1IU4oFbRdznvfjbzRTAMRDd9SWjfpu3LIvK5wxpdkOAA\/NUePj3FUrbY2uFHtBHAhMh6iSavgGWFNAQLleNDTvWzsnC6N8uIGlGbsj51aLf8AmoPQVSttnmYQ+GlR5zD6Y+KNeolP2v5A6Kh7kTbQO\/Vn4c8VG9XOB\/BeeYy1ufUQevTNGNsvOOSyShvMeACWHVpD25jjSg\/ohOUtceeSK51VfplUWn5Js7t2vBCHA+iOxQyt53AbzwWhyTG87ECOqhVcsrn2qknOTRQtGTy89GQ96tXLcb7Vj5NzW4MOTq54q0plTdvKzpW0HWUXfJyaGauh5P8AnQZZOMHJB4oqUkmU432yxuDcThTQP5zfs8PskInuf5Snso2dppxzeO\/zh\/uW24NcC00I0LXCo9+nWsO8tlYn5sPJngc2dh1Cj6qfcip42u1kt5fKM51eSGX7Iwj\/AFOq49wQvbr\/ALRIal9OrM\/6nVPdRULVZjE90ZpVpoaKIlPjCPIlzkcleSakkniTU95T7s+l+yfEKF5U10\/SfZPiEUuGDHkJuXKSrpKaig9VcngJA71zFQVOSnGiAzUkapPvGFusjOqoJ7hmq7toIRpjd1NI+9RcabKtRnJB1q2wjZ6LW+vI1vu\/qsu0fKCBkJGj1Iy497+aU6CfgXKvJ6K8rzr5U7DR0M4GoMTuyrmeL1iWj5QHuyDpT1uEY7mLDt20Jmye0AA11LidRqevgqMcZqV0IyOLjVlK0NqFJYg6VnJZl8YL4uJA+kjHTQBzfVI3qJ9sb9XvU9z3daJHiSztw0JGOuFrSRQ566HcDqnydIRFOw22MmDrMCNz3A9eTvBwW3KzQhVbkuwQQiOuI5lzuLjmT+dwV1vA6Lx8zTm2j1caaikyF7cQrvH5qmltcipXc0rkrBqEkYVuTGh700tpk7vU7c8t6Z0OXHGFf1xtlBcKB\/1tx6HfFCtsuB7W0DgfcOxH1qdhBOooe1BZt80rS9vJ0xEYTWoA061d6eeStnsvJLmhC91uYU1kLBV1OyqWJ2HLRPtVqc7JwHZ\/9VVkh0FF6EdVbkL02ckqUWbA5ct9j+ZC7HV1RVsVlyv2f5kvO\/8AzaGYV70FNphcSHNdhNKdB7dytWfFyYaXUd9bX0q6HuWa624X0dWh3\/n89av2dwIDgcivOdouQBbQn9Zl9c+AWa4q7tAf1mX1ys4lejHhEEuWJxVm6PpPsnxaqhKtXQfKfZPi1bLtZkeUEFElxcUpRZetW27jkHSnqwxj3Z+5ZM+0kjtGDre5z\/gsZIJywwXwL6sn8l+S+rQdHBo4Na0eIqqklokd50jz1uJ91UxdRqKXCBcmxjWBPDFxSRHMdYRGDv0ctAIGEGtMw5uXGtSpLkutj7Q+F2bWY99CcDqfii\/+0YNRIK9R+CCNm7QI52PNTTHpqascPxQRlOUXewTjCLQbN2WsrsNYyANwe7P1s6nvRDDZWsaGsaGtAyAFAEHN2nmldgs0OI8czTrJoG9qdeYt0cXLy2lrcLmnk2HMguANcNAaV0z0U8sc5UpSHKcVvFBrHwK5I388VWuy18tCyWmHECadRIqOg0qOtXA7JRSVOiqLtWRHnBMblkU53NNdy7JHXMaIAyF7KFcOY6U9jtxTHszWHGZfd4tiYDQOc4hrW7yTvoMz\/UIVtVjnkzkIjac8Iz8Mu8koive5OWkbIJC0tplStcLi4b8jUnNcmuve5zndfwVUMkYRWnn5EShKbd8AFarIG1AqetV2xI1tt1tIyAQ\/BYcRcBq00P57FZjzqUWyWeGmjKw9CKdi3Dyv2f5lRfdOSmuKQWZzmyGgfSh3DDXXv1XTmpwaXJsIOEk3wF74QRxUkLg0BugGipOtGE19E7\/z0fkK5E8OaHaVULRWmAF\/n9Yl9YrOJWhf394k9YrPK9KHCPPlyxpKuXQfKfZPi1UXBPstqLHVpXdTu+CNq1SMTphVVcWT\/bLfqu93xSU\/Tl4Ha4+SoutXEk8SdXU0J1Vxok5iauhcYctkcLSOTBr11zUdgskj3HkwcQzPo07\/AAWtddkbHznUL9ehvV8UrBaDy0pG+nuAXa9tjtO+46y3vabPlIwhvSOb3jKvUUTXdbbNaWgGmM7nUNegVyPVkehR2C0gCjhlv3hcm2ds0vOhdyTz9XzTXiw7uqimnKMtpbfaKIxlHgJ7LEGtwDQabtFIzVDOw19GVvIyGrxic061aMIoa51BPd1ImeKFR5IOEqZTjmpK0dLNyjjFDTcpdQq\/mlKYxDJozVObzh0qY5qvWhQmkRFCnPZi01UrwHDpUTciuZxQtVlxNcNKgjvyWDdFzSRF+MggnKmh6xTVF0jQ4dKrYdxRRyyUXHyC4JtMyZbHlloqtpuxkraHIjQ7x8Vvgdyhms29q2ORp2jnBNUwPitEtldgeMUe7hT9k7j+yf6opsFrY9gMZBHh0EbimzwNkGF4qDqD+dUNWuwS2ZxkiJw9+XBw3jp8FUpRy87S\/wCk7i8f2jPv7+8SesVnFWbbMZJC6mbjWg4ngp4btPnPHZ8VZailZLpcnsUYoC7q4\/nVXGWQaU7Vd5Cq62OhzS5Zb4GrGkUfmI4DvXVp4QuIOq\/IWhGKkuVXQqiazqS4Ulx1jqpwTE5i40tFxy7lLdkPlHDor7mfFPkjy0VOG3Fk3KN3NDSPrbyB3BJjck0vAx0mmwqijpQrQdZ2ObTTKmWXgnxWYFaFiswovPc5FiijM2duZkEjngklwI51DSpqQMuICI5hlVV8FCrEeYoulJyds1JLZEUbk+VtRRRUopmGqAMgiduXZ2Ap0zd6cw1CE0rRmhT5G1C7PFwTYn7ljOIgU5zcQ6V2ZiiYSDRCcQyMSY5WpG1zGqruXHEM8IOYUOuRVpr0nxgrUzjCluWNpL2NoT3DqG4KDkOhbzTTI9SZPZAcwm9VvkDQlwD7rHvGiTYdxC18FPz4rj4a6aouoZpM35qElb5A8Pcks1m6QJXQkkvXPMEkupLjBLrUklxpvH4odP032h4hdSSMPyOy8I9Ri0HUr9m0SSXnFrHS696fD+fekkuMGy7+zwCUGqSSwJD5NO1Mj1PWkksZo53mlVPj+C6khNRJJp2KtJu7EkljOJbPp2KCTVJJYcQyahSxbkklxxFaPz7k+LRJJazivLr2quxdSWnEiSSS44\/\/2Q=="
    },
    {
        "property_id": 10019,
        "title": "Large 2 Bed, 2 Bath with In-Unit Laundry",
        "property_type": "Apartment",
        "monthly_rent": 2300,
        "bedrooms": 2,
        "bathrooms": 2,
        "status": "",
        "city": "Capital City",
        "description": "Contemporary 2-bedroom 2-bathroom apartment located downtown, featuring a bright open layout and balcony views of the skyline.",
        "image_url": "https:\/\/encrypted-tbn0.gstatic.com\/images?q=tbn:ANd9GcToVSAFx9083fXhREulE2ihYjdfEgVxQzTKdw&s"
    },
    {
        "property_id": 10020,
        "title": "Single-Family Ranch near Parks",
        "property_type": "House",
        "monthly_rent": 2400,
        "bedrooms": 3,
        "bathrooms": 2,
        "status": "",
        "city": "Riverton",
        "description": "Beautifully maintained 3-bedroom 2-bathroom suburban home featuring a two-car garage, lush garden, and a cozy front porch.",
        "image_url": "https:\/\/i.pinimg.com\/236x\/15\/e2\/37\/15e2374e361329ab7960d23c8d6c559e.jpg"
    }
];

// get id from url
const currentUrl = window.location.href;
const id = parseInt(currentUrl.split('/').pop());

// finding propert by id
const property = properties.find(p => p.property_id === id);

// setting value in form
if (property) {
    document.getElementById('title').value = property.title;
    document.getElementById('property_type').value = property.property_type;
    document.getElementById('monthly_rent').value = property.monthly_rent;
    document.getElementById('bedrooms').value = property.bedrooms;
    document.getElementById('bathrooms').value = property.bathrooms;
    document.getElementById('city').value = property.city;
    document.getElementById('description').value = property.description;
} else {
    Swal.fire({
        icon: 'error',
        title: 'Property not found!',
        text: 'No property data found for this ID.',
        confirmButtonColor: '#00a651'
    });
}

// handle update button with sweet aert fronthand 
function handleUpdate(event) {
    event.preventDefault();

    Swal.fire({
        icon: 'success',
        title: 'Property Updated!',
        text: 'The property details were updated successfully.',
        confirmButtonColor: '#00a651',
        timer: 2000,
    });

    setTimeout(() => {
        window.location.href = '/admin/dashboard'; //take back to listing
    }, 1200);
}
</script>
@endsection
