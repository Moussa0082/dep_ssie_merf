<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// create some HTML content
$subtable = '<div class="well well-sm"><strong>P&eacute;riode et </strong><strong>Objet de la mission</strong></div>
            <div align="left" class="well well-sm">
            Supervision du 02-02-2015 au 15-02-2015        :&nbsp;&nbsp;</div>
        <table width="100%" border="1" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">

            <tr>
              <th rowspan="2" align="center"><strong>R&eacute;f.</strong></th>
              <th rowspan="2"><div align="left"><strong>Recommandation </strong></div></th>
              <th rowspan="2"><div align="center"><strong>Date buttoir </strong></div></th>
              <th colspan="2"><div align="center"><strong>Responsables</strong></div></th>
            </tr>
            <tr>
              <th><strong>D&eacute;di&eacute;</strong></th>
              <th><strong>Autres</strong></th>
            </tr>

                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                MISE EN Å’UVRE DU PROJET ET APPUI A LA COORDINATION SECTORIEL              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>07</strong></div></td>
              <td><div align="left" class="Style4">Réviser le plan de passation des marchés avec les agences d\'exécution et la DMP,avec des montants et des délais réalistes</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                APPUI FILIERE COTON,PROMOTION TRANSFORMATÂ° NOIX CAJOU CENT.NORD              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>08</strong></div></td>
              <td><div align="left" class="Style4">Introduction sous forme de test des innovations  sur le coton.  (facteur NH4, sarcleuse améliorée, e-extension, etc.)  </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/02/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">INTERCOTON/FIRCA</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>09</strong></div></td>
              <td><div align="left" class="Style4">Recrutement d&#8217;au moins un Ingénieur statisticien pour la conception des fiches d&#8217;enquêtes et le traitement des donnés à collecter par l&#8217;INTERCOTON dans le cadre du diagnostic des sociétés coopératives   </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/02/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">INTERCOTON</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>10</strong></div></td>
              <td><div align="left" class="Style4">Transmission à la Banque de la requête avec un argumentaire relative à la prise en charge sur le budget prévu pour l&#8217;assistance technique  d&#8217;une partie du personnel recruté par l&#8217;INTERCOTON  </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/02/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP/INTERCOTON </td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>11</strong></div></td>
              <td><div align="left" class="Style4">Transmission à l&#8217;AFD et à la Banque d&#8217;un programme d&#8217;activités détaillé y incluant les modes opératoires d&#8217;acquisition des matériels et des b½ufs</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                26/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">INTERCOTON</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>12</strong></div></td>
              <td><div align="left" class="Style4">Révision du cahier des charges du conseil agricole
Tenue d\'un atelier pour optimisation ressources mobilisées
Campagne 2015-2016</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">INTERCOTON/FIRCA</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>13</strong></div></td>
              <td><div align="left" class="Style4">Préparation d&#8217;un Plan d&#8217;Action pour l&#8217;Appui à la Transformation Locale de la noix de cajou (convention/accord + manuel de procédures + sélection des prestataires de services)  en tenant compte de toutes les initiatives existantes.</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/04/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">MIM, le Conseil du Coton et de l&#8217;Anacarde, l&#8217;UCP et le MINAGRI</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>14</strong></div></td>
              <td><div align="left" class="Style4">Adoption de la loi SRE, afin de pouvoir entamer les autres activités nécessaires pour la mise en ½uvre effective du mécanisme</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                30/04/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">MIM</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>15</strong></div></td>
              <td><div align="left" class="Style4">Redéfinition des activités relatives au système d&#8217;information sur les marchés et de mécanisme de fixation des prix, et ce sur la base d&#8217;un argumentaire. </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/04/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">Conseil Coton Anacarde</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>16</strong></div></td>
              <td><div align="left" class="Style4">Mise en place d&#8217;une plateforme des acteurs en vue d&#8217;assurer la coordination de la politique et stratégie nationales de promotion de la transformation d&#8217;anacarde

</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/02/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">MIM & Conseil du Coton et de l&#8217;Anacarde </td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>17</strong></div></td>
              <td><div align="left" class="Style4">Rédaction et transmission à la Banque d&#8217;une note relative au système d&#8217;information sur les marchés de la noix de cajou  en mettant en exergue les activités à faire financer sur les ressources du PSAC</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                30/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">Conseil du coton et de l&#8217;Anacarde</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>18</strong></div></td>
              <td><div align="left" class="Style4">Rédaction et transmission à la Banque d&#8217;une note relative à la recherche &#8211;développement sur l&#8217;anacarde, incluant les différentes sources de financement</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                18/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">Conseil du coton et de l&#8217;Anacarde</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>19</strong></div></td>
              <td><div align="left" class="Style4">Définition et mise en ½uvre d&#8217;une stratégie de diffusion à grande échelle des innovations post-récolte introduites par l&#8217;ANADER et ont fait l&#8217;objet d&#8217;adoption par les producteurs</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/02/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">Conseil du coton et de l&#8217;Anacarde/ FIRCA</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>20</strong></div></td>
              <td><div align="left" class="Style4">Rédaction et transmission à la Banque d&#8217;une note relative à l&#8217;appui aux PMEs de transformation d&#8217;anacarde  en mettant en exergue les activités à faire financer sur les ressources du PSAC</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                30/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">MIM, Conseil du coton et de l&#8217;Anacarde et le MINAGRI</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                MISE EN Å’UVRE DU PROJET ET APPUI A LA COORDINATION SECTORIEL              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>22</strong></div></td>
              <td><div align="left" class="Style4">Transmission des documents relatifs aux activités ayant fait l&#8217;objet de préfinancements par les Agences d&#8217;Exécution pour remboursement des dépenses éligibles </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">AEP/UCP</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>24</strong></div></td>
              <td><div align="left" class="Style4">Requête à la Banque mondiale pour le réajustement de la période couverte par le premier audit à fin 2015.</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                31/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">Coordonnateur
SGF
</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>25</strong></div></td>
              <td><div align="left" class="Style4">Transmission à la Banque de la programmation de la réhabilitation et de l&#8217;entretien des routes pour toute la période restante du Projet. </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                31/03/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">AGEROUTE</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>26</strong></div></td>
              <td><div align="left" class="Style4">Réalisation d&#8217;une étude de faisabilité pour la mise en place de centres de prestation de service mécanisé</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                31/08/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">DGPPS
INTERCOTON
</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>27</strong></div></td>
              <td><div align="left" class="Style4">Recrutement du 2ieme spécialiste en passation de marchés pour l&#8217;UCP et des spécialistes au niveau des AEP </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/02/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">DGPPS/MINAGRI / Unité de coordination du projet</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>28</strong></div></td>
              <td><div align="left" class="Style4">Préparation d&#8217;une stratégie de communication visant à (i) établir une meilleure coordination entre les AEP, (ii) mieux informer les groupes cibles et partenaires du Projet sur les programmes des interprofessions et mécanismes de financement du PSAC</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                15/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP et AEP</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>29</strong></div></td>
              <td><div align="left" class="Style4">Organisation de missions d&#8217;appui à l&#8217;UCP et aux AEP</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                29/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP/AEP/BM/AFD</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>30</strong></div></td>
              <td><div align="left" class="Style4">Organisation de la 3ème mission d&#8217;appui à la mise en ½uvre </div></td>
              <td valign="top"><div align="center"><span class="Style46">
                03/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP/AEP/BM/AFD</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>01</strong></div></td>
              <td><div align="left" class="Style4">Les filières mobiliseront immédiatement leurs contreparties  destinées pour le financement des travaux et études programmés</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">AEPs</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>02</strong></div></td>
              <td><div align="left" class="Style4">Des dispositions idoines prises prises par l\'UCP et les AEP pour assurer une application correcte et effective du
screening ,de l\'analyse et du suivi adéquat de la mise en oeuvre de sauvegarde environnementales et sociales</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP/AEP</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>03</strong></div></td>
              <td><div align="left" class="Style4">Un suivi rapproché des prochains travaux de réhabilitation  par les agences d\'exécutions afin d\'éviter tout retard dans le programme</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">AGEROUTE/AEPs</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>04</strong></div></td>
              <td><div align="left" class="Style4">Veiller à l\'utilisation efficace et efficiente des ressources dans la rémunération des entreprises ,des bureaux de contrôle intervenant dans ce volet</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/07/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">AGEROUTE</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>05</strong></div></td>
              <td><div align="left" class="Style4">Procéder aux mises à jour nécessaires du manuel de procédures du PSAC</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>06</strong></div></td>
              <td><div align="left" class="Style4">Programmer l\'entretien des 153 km réhabilités dans le dernier trimestre de 2015 et procéder très tôt au lancement des travaux y afférents</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">AGEROUTE/APROMAC/AIPH</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>21</strong></div></td>
              <td><div align="left" class="Style4">Finaliser le processus de formation des utilisateurs du logiciel de comptabilité TOM2PRO</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                17/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP</td>
            </tr>
                                    <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left"><strong>
                              </strong></div></td>
            </tr>
			   			             <tr>
              <td><div align="center"><strong>23</strong></div></td>
              <td><div align="left" class="Style4">Justifier entièrement les avances</div></td>
              <td valign="top"><div align="center"><span class="Style46">
                30/06/15              </span></div></td>
              <td valign="top"><div align="left">
                Administrateur              </div></td>
              <td valign="top">UCP</td>
            </tr>
                                </table>
        <hr id="sp_hr" />
      ';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
