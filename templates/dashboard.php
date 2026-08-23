<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$user     = wp_get_current_user();
$nome     = $user->first_name ?: $user->display_name;
$hora     = intval( date('H') );
$saudacao = $hora < 12 ? 'Bom dia' : ( $hora < 18 ? 'Boa tarde' : 'Boa noite' );
$has_woo  = class_exists('WooCommerce');
$has_estatik = post_type_exists('properties');
// Classe do body: 4 colunas com Woo, 3 sem
$body_class = $has_woo ? 'iv3-body iv3-cols-4' : 'iv3-body iv3-cols-3';
?>
<div id="iv3db">

  <header class="iv3-header">
    <div class="iv3-hL">
      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGoAAAA0CAYAAABipa0QAAABAGlDQ1BpY2MAABiVY2BgPMEABCwGDAy5eSVFQe5OChGRUQrsDxgYgRAMEpOLCxhwA6Cqb9cgai/r4lGHC3CmpBYnA+kPQKxSBLQcaKQIkC2SDmFrgNhJELYNiF1eUlACZAeA2EUhQc5AdgqQrZGOxE5CYicXFIHU9wDZNrk5pckIdzPwpOaFBgNpDiCWYShmCGJwZ3AC+R+iJH8RA4PFVwYG5gkIsaSZDAzbWxkYJG4hxFQWMDDwtzAwbDuPEEOESUFiUSJYiAWImdLSGBg+LWdg4I1kYBC+wMDAFQ0LCBxuUwC7zZ0hHwjTGXIYUoEingx5DMkMekCWEYMBgyGDGQCm1j8/yRb+6wAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAABmJLR0QA/wD/AP+gvaeTAAAAB3RJTUUH6gQGDx8sXz5OGAAAGJxJREFUeNrte3l0lFWe9nPf+2711pKkErInkLCIICgi9oy2CIqNiFsjbqPTrdOCes6M2qcdnFY8zjD96Uy3y+iMo93j9mkr6nfadkVB3DAKogiIkBDIvlRVlqpUUlXvfu/8kaqYVAJEoE/8zuE5p0Lx3vv+frd+z3vv/d3n3hc4gRM4geMHMtENOFZ88cUX4Jz7VFWdLQiCOLyMc046Ojpaf/zjH7cGAoGJbuoxQTx2ExOLadOmgXM+XdO0PwuCkAOAZ8oYY6IkSev8fv/9E93OY8X/90QpigLOOVUURRMEQRtexhiDLMvyRLfxeECY6AacwPhw1D3qJ8uuAbhIxOIiJW96OetoC6Ft37cIffMVUn1dDkDYsFHoBI4RR0XU9ItuwI9fexY7//jxotzJU/+Raj5hisNYhWXBtUyuBnKjgigbjmkQx9LhGClIBKYvv6jHNi1umimYeopw5tq5JRXdzHFdM5UgVioBJzmAgD+nX5tUmDTa6wfa3vy/X1DFY7z3zMOHa1ISwC4Afox8OiiA0EQH+XjgexNVvWgJbnrnWXz+P5sX5s047UkaCM7gIBBHGOMAB0RZhYjcoas2ACgcMs+DnI4nJwKIzKF4/FDyOcAYBEHgpqUnDEG954LQG3WyaCsXXVgcv+298Kj2MMbAOa/nnF+cXcY5h2EY9l8ygFu3bkVdXZ08d+7cKcFgsJoxVpafny/7fD5OCIFhGOju7maWZYXj8fj+ffv2NRUWFlrLli37Xn6+V3p+zjWrMPdf/oBwzUdn+6pnPyPl5M/gEED4oCUODH7PBOoQ1jOX+RjXAICZKaO/rfFfLv1/F7+yoDD+m6hN/5MSbPur30dH2aqpqQEABcDs9L/f2WEMkUikoaWlJVVZWTlbkiSa7dY0zcjVV1/dcN555+Gjjz4CAMybNw9ff/01nnvuuerc3Nzi7Htc12WJRGL/nDlzYpTSmcXFxXcHAoHzRVEsEARBJoSAEDKiHYwxx7btrv7+/ncbGxvvLy0tbbzmmmvwxRdfjCv24+5Rv/zt0xi44G9htbeemjvz9MeJ6p/BQYZIGgr2sIgf6SkYThhPs8zMlDHQ1rTugpeWv7y4KvafXsVZ2tDqeTFHG9vavHnzAKCYEPISgIrhQXVdF21tbbd6vd6t8+fPX69pWhHnfMTQmEgk3li7du3frlixws4QtXz5chBChJaWlrvz8vKuB+AMtZkQWJaVbGpqulzX9QNz5sz5b5/Pt1gQDp2XUUpBKRUlSSpVFOUXiqIU7dy582ePPPJI7Kyzzjp+RF35m8eBy/8OvPbb+aS46g9E8pyaCTEn5JhXzQQcIATM1PWB9uZ/Pe/ly9dfNKX7sWKvfbHpCo4AiOIhnGialuFcS3+G4DgOVFWVu7q6ulRV7ff5fFXZ9wuCMH3x4sU+znksc62qqgq///3vPYFAYIbf71eQ1VN1Xe+1bbt98uTJl2iadu7hSBqLNJ/Pd2FZWdmyyZMnv3TmmWdi+/btR7xvXB6MVBKPzCDEtO1TXENnzDb6OXeRidAgZek/6U/m62ExrB4zU0Yq0vbPP3lx2frllV2PFfmcSyhhEMCRqzjwS86RrI3tgnPy85//vN+27YaRnWkocEXBYDA/Pz9/6Fp1dTVmzpyZI0lS8Vg2TdPseP/99/sVRVlEKRWy/IExxhljbvqDbL+iKIrBYPAcVVWxdOnScf2OcfWot+5fg+rzLuN/XHn68z99cP0b2uSZM7nPt0hUfOdRxXuaICsFRBAJJ2SIHgIAnICTQw+Bg2UczNKNgVDLuoUvXP7qhZN7HivxWpfQ9GQnCECB14VHOiqehgd3P+d8xNyRJirX6/UWq6p6MHOtoKAAtm0XUkrzs+1wzmHb9gHTNG1VVcuz7RmG0RmNRh/y+/0HbdumABbm5ubeQilVM3UIIaCUTr3vvvtUv99vHDeiAKDxwzcAgP/5zmv7vLml2xKxjm2L/uk/Hs2dNnu6b1LpX1Fvzvmix7+AyGo5oaLEiQAyrLtlEotMsjGcpERn67pFL1y4fnll9LFiv3OJSDg4OAgIRMKR7xEgCUe9JiOpVAq6rtcxxrggCCMiSwjRFEWZnJ+fX5O5lp+fj3g8Xi4Ign8sokzTrCWEiK7rBrPLksnkxvLy8kceeughfsEFF4AQUuP1es+nlM4ZXldVVe2iiy6i2UQfM1HDkezrzDyZOoBvrnj6o2/qXnrs2elLriz1TCo/Q/JoS6jHezZRPNMEUfZAEDA4k/HvCAMBtwwjEW5fd9YLP11/cWXPo6V+8xJKOIblJwAg6A5XPBo7WqKg6zpc161njA0AGKHOCoJAZFme6vV6cfXVV+OVV15BSUkJXNetpJSO6seu6zqu6+6rqqoihBCaXZ6bm9vBOeeEEOzZsweccx3AQHY9VVVx6qmnjvs3HBet70+/WAwA9t5X/9By0vIbW6IH9/z5Rzf8skCtmHaqGixcKHkDC4kin0KoHCRUBAjATN1IhjvWnfvcT9ZfNLn30WK/cyklHIQPJiiD4GCcC6EBN+hXj759juMgHo+3O47TLcvyCKIIIRAEYRohhNx66638pptuAgBQSqeOlSQwxuKJRKIpEAiQ7N5ACIEoimxYXYw1L2bqKoqC8eK4i7L733kWANjbv76ua1LlzPe7W+s2X/TgH71qfvnJij94rpJfeA6h4lw9Fv2fM5+/fP3yyu5HS/3WpeKwBRgZ+stBAPg1Sig5umQCACRJQn19ffSkk05qBTB1jIBVPPHEEx5ZllNdXV1YsmSJ+Oqrr04Za1hyHCfU29sb1nXdjcViuxzHSQLIkEMBtB3vmAJ/YfW8u7UOAPiGO69PSJL85c83NHzZtOHF//JUnzLptPfvES8qafvv0oCzTCTsu540RnCSJsnxVh39IkCSJFx33XWpSCTS4Pf7F2cTIIpi6YwZM3IYYymfz4ebb77ZK4piebaddCLRvHnz5vhHH31kh8PhW1RVze521njb5TgO/qJz1NHAti08dUEFABibbixo4wHMKvFbp4tkMM3HYbJD0xH8KDx6oigdnEpM06xljA39fygIoliQl5dXKIpiSFVVAAhSSkel5pxzWJZVt27dOmvNmjVYs2ZN6nB+hw17oxqv6zr27NmD8a7BJmQ/akejjV5dbp2VJ+zzSygaTOXH+DmD62BolAPusSnxXV1dME2zjjFmUUpH7FGJoujLy8sr1TRtt6ZpsG27SBTFvGwb6YyvjjGG3/3ud0f0aVkWDMMIMsYKsstM04z/6U9/sktKSsbV/gkh6uU9Onb9wUzs/VD6oEizF4uCO3ZFMpjOBxQHGDj6rA8Auru7wRhrcl03JklS0Qg3hMiiKFYVFhZCkiT09vZWCoLgzbbhum5K1/UD7e3tI66HQiGEQqE5s2fPvlyWZWTWa8lkUtJ1/UxFUaqH12eMwTTNr3/7299a119//bjaPyFE7Y5a+PZDH1IW/9BmJC4JyDm0DsXhVziQHPfQPyY6OztBCIlUVVV1AhhBlCAIkGW5GgDy8vKg6/rkbMUBABzH6enr6ztUsnAqgHXDL2iaBk3ThuYhzjk450gkEnu6u7vXG4aBF198cVztn7Ad3u0tMna0a7UDFq0dkp/GAAHgkQlgAoXH4G/37t149NFH+w3DaB4rZRZFsWrFihVi+vvUQ2R8be3t7d2h0OG3uDL3ZqvolmXx9vb2mt27d986Z86cfWvWrBl3+yeMqJd26bj13a6+uClscbmQ0ZzGrNudYAVYC5Qdw1rqySefxFtvveWYplmXTRQhBJIkVd5www2+e+65R5YkaVRqnk4kDl5xxRXJ8YioY0GSJFJUVDRv1qxZt2/fvr3kvvvuG/e9E3a45YMOHfV3BBC38b7N7L8XKdO+29DKepoJVwBg9hSCnXVHl1Q0NDRA13UYhjGmlCSKYlFxcXHukiVLBFmWS7Pvz2R8GdVhOGzbhm3bruu6Kdd1RyxyCSGKIAg007sURfFSSq8sLS39ory8/KH58+djx44dR2z/hB5u+boFOBAhe1K20JAmJGu3cfC7Jg82c8FJwe/rYgQikQgcx6lnjCVGBUIQ8jRNK/Z4PPmEkFGjrOu6jq7r+3t6ekbZbW5uRnt7+0ddXV3Lent7L4xGoxdGo9ELY7HY0s7Oznssy0oB3w2JoihC07SzV65cKa5cuXJcbZ/Q42Lbmhj+Y1ci0nS7tyZXtecIaSXiOwzm5yajPkL+mv508h73KF0BGMzOCCHtFRUVXbIsjxBcKaWa3+8vEwcx6rSm67rxVCrVaJrmKLsLFy4EgHD6M4TPPvsMhJC9BQUFVwE4fXiZLMuFq1atUgRBGJfkMqFEPbo7gTW/8iEygE+KfXSVSrmYPfQRAIaFQIHQLpop+5iIamxshGma0ZNPPnmUlCQIgiBJUrXrupogCJ7sex3HCUcikdChtLux4PP5wDm3CSGjtjI0TcPChQt/eMrEofBpkwjGyRfTC+xWD0X1iIVvejNLpS7OLLEwM4djQ/fR+2pqasLatWtTPT099ZzzEVJSOqGoZoz5stWCtHTU9MEHH/RVVFRg5cqVEASBVFZWSrIsZ0faBeDcf//hD+cSQpBWQcaFCSdqV7OJf9sZbG+9Q98aVEk1Gcr8eJonAk1iOGeqi1JNAPYdva933nkHa9euRSKROJCXlzdCSkpv5k2XZTk4lqzjum7tAw88YK1atQqXXXYZBEHwn3322f/u9/unYqQo+zyAF45E1PfFhBP15k4d8bujTmOUbi7ykr+RhcHzTIOz1SBtHongr2dI8IkeAOPaEB0T27ZtyyQUtWNJSbIsnywIwqhJiDEGXdfrLMvCU089heXLl0MQBCkvL+/sQCAwJ6v653+JOE04UfsAbG0VYTNsq8ohnbKMMuC7BNDhAnp0KfZ5m5fNLSIAYsfkLxwOgzHW7DjOKCnJ6/WWZtSD4cOi67qpRCLR4LqDU+Sh5qnM9eH3Hm5OY2z8stiEEwUANU0C9vZ6muYVO18FZLcsMzcxThBKKDWbDnj+z61nJO05/95zzL4ikQgIIWHXdUdJSdlKQgZp6ai1r68PABCLxUAp5a47WimORqOT8vPzcfvttyMWi0EQBJkxpmXXsywLDQ0NP2z1PBv1LUm8dotlHozQTQ6jl4kCgwMB4aS8ZXeHsGpR5UD9mU940e4c+6HXXbt2YcuWLf1nnHFGI+d83niyLtu229rb23syQfX7/eCcG5Ik9Q2vl17QLj5w4MDF9957b5NlWQIh5FxJkqZm29R13diwYYPr8XiO6B/4gbzN8Wq7g88aJLTG6VbdEXoZCEIJ5ZPPOryryovV+qXP5+NAtPe4+Gpubsbbb789ppQ0FjLS0VVXXZX49NNPAQDffPMN7rjjDtMwjEi2Da/XO3PKlCmv5uTkfDJp0qSPCwoKHhFF0Z9tk3PeeOeddxq2Pb6H7wdBFADs7CTY1i7V9zniN+GksmVPp3LTwlKj/rz/EtCY7Dhufp544gmkUikYhrGfMXbko4fpPSjOOY/H4wCAL7/8Ep2dna5t21uz55l0mu8RRTFfFMWgKIpidq91XZclk8mtjDHs3LlzXO3+QQx9APD2Nwm81+kk31tV8oBtsJaKIn7wrvcl9PHIcfcVDodhWdYB13UT2U97NoZLR8899xwAYOPGjaitrYWu628FAoFfaJo2e7wL1/Q2x9cNDQ2bOjo68Pzzz4/rvh8MURtDbmYif//73He4Uz6HQmdnJwB0VFRUdCmK4j+cDdd147quj5KOPv30U6xevfrgwYMH/z4/P/9+r9c7TxRF9VB+OedwHMdJJpM7mpqa1px77rkdv/rVr8b9O38wRB0jYgAeADD0Du+w4I/ak0hnW90nn3zyb1RVLeGcj5knE0KIZVl9PT09TdlD3M0334yHH34YdXV1H3/wwQfLKysrF/j9/tmc8wq/3y9lNgzTR9VgWVZXMpn8trW19fPzzz8/smzZMrz33nvH59e7rgvXdaeGQqGfTJs2Tfj1r389qtxxnBmxWGwe5xwbNmz4XvYdxwHnXNJ1fWFbW1tBf38/bNueGgqFls6fP1+4++67R9RPzy1nNTQ0nM45R2trKzo7Oz2NjY0379ixo/yrr74a009zczOam5sv3rt373np3vS9YuA4zvTOzs4LAJC77rprRPsdx6ns7+//0TXXXEM2bdp0RHu1tbWora09Z8eOHT/lnOPZZ58dVzsO26Oi0SgATOnr61t88ODB91evXl15++23r3Qcx2lsbHxNEIT23t7e2zjns5955pnrZFkO79u37wJRFGcmk8mPTzvttN0ff/xxzqRJk64IBAKRp59++t2VK1eeIsvyYsuydlFKP9m+ffuUWbNmPTkwMPBMeXn5gz09PZP7+voW7dixY9ODDz74o+XLlwcAWOk9n09SqdSC/v7+vmg0+nU0Gp0eDAavKioqulZRlC9LS0vbv/322/kej+csx3G+mjFjxtaamhq/aZorSkpKbvL7/W/5/f4Pd+/ePU1RlGWc87avvvrqPVEUjWuvvRabN2+G4zhTcnJyFpimuV/TtGpBEF7v6empjsfj5wDYvHTp0rwbbrhhhc/na6eUboxEIj+TZXnFlVdeeS2Aptra2ksVRWk0DKOqtbX1rUAgIBQXFy9njAX7+vrerqioCCmKcnoymZwG4M+lpaVHZulIROXl5QFArm3bVZxz7rruDMuyfsY5d6qrq/sBPCfLMuecs2Aw6E6dOrWwrKzsXkEQBvr6+s4EcN2CBQvmMMb+saWl5dFNmzYJd9xxx1WU0rMALOvo6NgeCARY+lUUN+0z17btas45b2xsnK2q6nLXdQ3DML4JBAKfKIpSlkql1GAwCFmWVziOcwmldGiroKys7FpK6XwAS7788ssr5s6dO4Mx9ktRFA3XdSFJEkpKSlZrmjZP1/VgVVVVA+d8DwAUFhYCQEFhYeE/JBKJrZqmlQB4PS8vL8+27Smcc97f338GgDs7Ozv/DQAURWGSJPH8/Hw3GAyq5eXlN2ma9k4qlVqWSCQ2VldXFymKciPnvHTSpEmuLMvPCIIwbtU8g8Om54IgQBAEQiklAEApJYyxVkrpXo/HoziOA6/X28Q5//byyy+P+Hw+SZZlxePxWKIoUkII8Xq9EqW0MRaLPb9161bXMAyHcy6qqqqUlZXRmTNndlFK20RR3JZMJkf4c123XhCEEkpptSAIe9Mr/aFyVVXFVCq1E0CYpGGapsM5lzwej7JgwQISCASo67ohxthupHV5RVE0RVG4JElcURQxc7S4u7sbiUSigxDCZFk+03Xd+nQcSGZHOBAISJTS/a2trett20ZOTk4T53z/okWLGiilAqU0EzfIssw7OzupZVkOpVT1+/2yKI7sG+Ml7KjWUWNoWQohhGTeDerp6ekKh8Ntw9/uk2WZv/jii5LH4znVtu1WzvnwNIpwzrXhsj8hRNB1vYUQkkspLUkmk426rg/3mXmYVM65CACvvPKK7PF45lqW1cw5t4fZkjjn6vA2G4aRDIfDB7q6uozu7sG9k1AohLq6uihjLK4oynzLsuoyPg8DDkB+/fXXZdd1wRgTLMvyY/C1Ux4MBsskSSqwLKsZYxzEHO/+1hGJ4pwnOedd6e86gDDnvJtz3m8YBjo7O8MATm5ubp7R0tLSn0wmWyilszRNs9L3GJzzMOec33XXXU4qlWpQVXWmYRh90WiUbd682RkYGOhVVXUppTTjLxIMBkldXV2vx+PZ6/F49ofSR38451HOeR/nHL29vc1+v382Y8xjmqa+evVqW9f1Ro/HM1PX9WgsFkNLS0uUUkokSTrFtu0YAAwMDOw3TbPI5/PlEkL0jDT0xhtv4MYbbzQlSdrp8/na+vv765PJJDjniUwMGGMpznkYAHp6ehCJRLo456Xz58+ft3HjxmQymWxnjF3qOM6AZVm8r6+vy3VdVxTFong83meaJjjncc758ZFagKGszxMOh/PTvUVNJBIFqVQq2N3d7aurq8P69es94XC4rLe3V+WcY9euXcHe3t7KN9980xuPx+G6rtrf31/w+eefk2QyiQ8//NAfjUYra2pqCh9++GEBANm+fXt+PB4vvOWWW4jjOJ5QKJQ/a9Ys8tprr5FUKlUQjUYL77//fqGpqQmGYeQ0NDT429vbsXnzZiUWi5VHo9GympoaWdd1bNmyJScajVZu2bJl0r333ksef/xxoaWlpcgwjMq9e/f66+vr8eabb6o9PT0Vu3fvnrRo0SJy2223jXjCk8lkIBKJlD311FPy3r174TiOFgqFgum1kNrf35//7rvvkpdffhkvvPCC3NHRURaNRjXOObZu3ZprmmZla2tr4fr164U1a9bQgwcPFre2tla89tpr3v3798O2bV8sFsvlnB+/FP0ETuAETuAETuAETuBw+F+V0DFrjqfuZQAAAB50RVh0aWNjOmNvcHlyaWdodABHb29nbGUgSW5jLiAyMDE2rAszOAAAABR0RVh0aWNjOmRlc2NyaXB0aW9uAHNSR0K6kHMHAAAAAElFTkSuQmCC" alt="iv3" class="iv3-logo">
      <div class="iv3-hInfo">
        <span class="iv3-site"><?php echo esc_html( get_bloginfo('name') ); ?></span>
        <span class="iv3-greet"><?php echo esc_html( $saudacao . ', ' . $nome ); ?> 👋</span>
      </div>
    </div>
    <div class="iv3-hR">
      <span class="iv3-clock" id="iv3clock"></span>
      <a href="<?php echo esc_url( home_url('/') ); ?>" target="_blank" class="iv3-btn-site">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        Ver site
      </a>
      <a href="<?php echo admin_url('profile.php'); ?>" class="iv3-avatar">
        <?php echo get_avatar( $user->ID, 34, '', '', ['class'=>'iv3-av-img'] ); ?>
      </a>
    </div>
  </header>

  <!-- Stats -->
  <section class="iv3-stats">
    <div class="iv3-stat iv3-c-indigo">
      <div class="iv3-si"><?php echo iv3_ico('page'); ?></div>
      <div class="iv3-sb"><div class="iv3-sn" id="sn-pages">-</div><div class="iv3-sl">Páginas</div></div>
      <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="iv3-sa"><?php echo iv3_arr(); ?></a>
    </div>
    <div class="iv3-stat iv3-c-amber">
      <div class="iv3-si"><?php echo iv3_ico('post'); ?></div>
      <div class="iv3-sb"><div class="iv3-sn" id="sn-posts">-</div><div class="iv3-sl">Posts</div></div>
      <a href="<?php echo admin_url('edit.php'); ?>" class="iv3-sa"><?php echo iv3_arr(); ?></a>
    </div>
    <div class="iv3-stat iv3-c-rose">
      <div class="iv3-si"><?php echo iv3_ico('users'); ?></div>
      <div class="iv3-sb"><div class="iv3-sn" id="sn-users">-</div><div class="iv3-sl">Usuários</div></div>
      <a href="<?php echo admin_url('users.php'); ?>" class="iv3-sa"><?php echo iv3_arr(); ?></a>
    </div>
    <?php if ( $has_woo ) : ?>
    <div class="iv3-stat iv3-c-emerald">
      <div class="iv3-si"><?php echo iv3_ico('product'); ?></div>
      <div class="iv3-sb"><div class="iv3-sn" id="sn-products">-</div><div class="iv3-sl">Produtos</div></div>
      <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="iv3-sa"><?php echo iv3_arr(); ?></a>
    </div>
    <div class="iv3-stat iv3-c-cyan">
      <div class="iv3-si"><?php echo iv3_ico('orders'); ?></div>
      <div class="iv3-sb"><div class="iv3-sn" id="sn-orders">-</div><div class="iv3-sl">Pedidos 30d</div></div>
      <a href="<?php echo admin_url('edit.php?post_type=shop_order'); ?>" class="iv3-sa"><?php echo iv3_arr(); ?></a>
    </div>
    <div class="iv3-stat iv3-c-emerald">
      <div class="iv3-si"><?php echo iv3_ico('revenue'); ?></div>
      <div class="iv3-sb"><div class="iv3-sn" id="sn-revenue">-</div><div class="iv3-sl">Receita 30d</div></div>
      <a href="<?php echo admin_url('admin.php?page=wc-reports'); ?>" class="iv3-sa"><?php echo iv3_arr(); ?></a>
    </div>
    <?php endif; ?>
  </section>

  <!-- Body: 4 cols com Woo / 3 cols sem Woo -->
  <div class="<?php echo $body_class; ?>">

    <!-- Posts -->
    <div class="iv3-card">
      <div class="iv3-ch">
        <span class="iv3-ct">Posts recentes</span>
        <div class="iv3-ca">
          <a href="<?php echo admin_url('edit.php'); ?>" class="iv3-lnk">Ver todos</a>
          <a href="<?php echo admin_url('post-new.php'); ?>" class="iv3-new">+ Novo</a>
        </div>
      </div>
      <div id="iv3-posts" class="iv3-list"><div class="iv3-skel"><s></s><s></s><s></s><s></s></div></div>
    </div>

    <!-- Páginas -->
    <div class="iv3-card">
      <div class="iv3-ch">
        <span class="iv3-ct">Páginas recentes</span>
        <div class="iv3-ca">
          <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="iv3-lnk">Ver todas</a>
          <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="iv3-new">+ Nova</a>
        </div>
      </div>
      <div id="iv3-pages" class="iv3-list"><div class="iv3-skel"><s></s><s></s><s></s><s></s></div></div>
    </div>

    <!-- Produtos: só aparece se tiver WooCommerce -->
    <?php if ( $has_woo ) : ?>
    <div class="iv3-card">
      <div class="iv3-ch">
        <span class="iv3-ct">Produtos recentes</span>
        <div class="iv3-ca">
          <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="iv3-lnk">Ver todos</a>
          <a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" class="iv3-new">+ Novo</a>
        </div>
      </div>
      <div id="iv3-products" class="iv3-list"><div class="iv3-skel"><s></s><s></s><s></s><s></s></div></div>
    </div>
    <?php endif; ?>

    <!-- Ações rápidas -->
    <div class="iv3-card">
      <div class="iv3-ch"><span class="iv3-ct">Ações rápidas</span></div>
      <div class="iv3-grid">
        <a href="<?php echo admin_url('post-new.php'); ?>" class="iv3-tile"><?php echo iv3_ico('post'); ?><span>Novo Post</span></a>
        <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="iv3-tile"><?php echo iv3_ico('page'); ?><span>Nova Página</span></a>
        <a href="<?php echo admin_url('media-new.php'); ?>" class="iv3-tile"><?php echo iv3_ico('media'); ?><span>Upload</span></a>
        <?php if ( $has_woo ) : ?>
        <a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" class="iv3-tile"><?php echo iv3_ico('product'); ?><span>Produto</span></a>
        <a href="<?php echo admin_url('edit.php?post_type=shop_order'); ?>" class="iv3-tile"><?php echo iv3_ico('orders'); ?><span>Pedidos</span></a>
        <?php endif; ?>
        <?php if ( $has_estatik ) : ?>
        <a href="<?php echo admin_url('post-new.php?post_type=properties'); ?>" class="iv3-tile"><?php echo iv3_ico('property'); ?><span>Novo Imóvel</span></a>
        <a href="<?php echo admin_url('edit.php?post_type=properties'); ?>" class="iv3-tile"><?php echo iv3_ico('property'); ?><span>Imóveis</span></a>
        <?php endif; ?>
        <a href="<?php echo admin_url('themes.php'); ?>" class="iv3-tile"><?php echo iv3_ico('theme'); ?><span>Aparência</span></a>
        <a href="<?php echo admin_url('plugins.php'); ?>" class="iv3-tile"><?php echo iv3_ico('plugin'); ?><span>Plugins</span></a>
        <a href="<?php echo admin_url('users.php'); ?>" class="iv3-tile"><?php echo iv3_ico('users'); ?><span>Usuários</span></a>
        <a href="<?php echo admin_url('options-general.php'); ?>" class="iv3-tile"><?php echo iv3_ico('settings'); ?><span>Config</span></a>
      </div>
    </div>

  </div><!-- /.iv3-body -->

  <footer class="iv3-footer">
    <span>Desenvolvido com ♥ por <strong>iv3 – Interatividade Virtual</strong></span>
    <span id="iv3sys" style="opacity:.5;font-size:10px;"></span>
  </footer>

</div>
<?php
function iv3_arr(){ return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>'; }
function iv3_ico($n){
  $i=[
    'page'    =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
    'post'    =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
    'users'   =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'product' =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>',
    'property'=>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/></svg>',
    'orders'  =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    'revenue' =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
    'media'   =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
    'theme'   =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
    'plugin'  =>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/></svg>',
    'settings'=>'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
  ];
  return $i[$n]??'';
}
